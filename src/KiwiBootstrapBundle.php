<?php

namespace Kiwi\Contao\BootstrapBundle;

use Kiwi\Contao\BootstrapBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class KiwiBootstrapBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }

    /**
     * Bundle configuration. The `grid` layer holds spacing/grid subsystems whose
     * ids are chosen by the wiring (gutter, spacing, container_padding_x, …).
     * Each subsystem may declare:
     *
     *     kiwi_bootstrap:
     *         grid:
     *             gutter:
     *                 options: [space-0, space-4, space-6, auto]   # space-N + subsystem-declared keys
     *                 defaults:
     *                     default: space-6                          # generic, whole subsystem
     *                     header:  space-4                          # a partial
     *                     footer:  { xs: space-4, lg: space-6 }     # responsive partial (xs required)
     *                 variables:
     *                     section-gap: space-6                      # semantic name => option
     *
     * The shape is validated here; membership/resolution (an option must be
     * `space-N` or a key the subsystem's wiring declared) is validated when the
     * config is processed, see {@see \Kiwi\Contao\BootstrapBundle\Configuration\Grid\GridStyles}.
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('grid')
                    ->info('Spacing/grid subsystems, keyed by a subsystem id chosen by the wiring.')
                    // Keep hyphenated subsystem ids (e.g. "row-gap") intact — Symfony
                    // otherwise normalizes dashes to underscores, breaking the id match
                    // with the registry and the --kiwi-<id>-* variable names.
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('subsystem')
                    ->arrayPrototype()
                        ->children()
                            ->arrayNode('options')
                                ->info('Concrete options offered (space-N or a subsystem-declared key; "default" is built-in). Lists are additive: a plain or "+key" entry adds, a "-key" entry removes — so a project tweaks the shipped set without repeating it.')
                                ->scalarPrototype()->end()
                            ->end()
                            ->arrayNode('defaults')
                                ->info('Per-partial defaults. Key "default" is the generic default; other keys are partials. Value is an option key or a {xs: …, <bp>: …} responsive map.')
                                ->useAttributeAsKey('partial')
                                ->variablePrototype()->end()
                            ->end()
                            ->arrayNode('variables')
                                ->info('Semantic name => option key, exposed as --kiwi-<subsystem>-<name>.')
                                ->useAttributeAsKey('name')
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $grid = $config['grid'] ?? [];

        // Options lists are additive across config sources (bundle prepend +
        // project): resolve the +/- deltas into a final, plain key list before
        // exposing the parameter, so nothing downstream sees the +/- syntax.
        foreach ($grid as &$subsystem) {
            if (isset($subsystem['options'])) {
                $subsystem['options'] = $this->resolveOptionDeltas($subsystem['options']);
            }
        }
        unset($subsystem);

        $builder->setParameter('kiwi_bootstrap.grid', $grid);
    }

    /**
     * Resolve an additive option list into a final key set. A plain or `+key`
     * entry adds the key, a `-key` entry removes it; the final set is adds minus
     * removes. Resolution is order-independent (the bundle base and project
     * deltas merge in an unspecified order, so left-to-right processing is not
     * reliable) and removing an absent key is a no-op.
     *
     * The result is ordered by scale value — `space-N` ascending by N, any other
     * key (e.g. a subsystem `auto`/`none`) after, in first-seen order — so the
     * dropdown stays value-ordered regardless of how entries were merged.
     *
     * @param list<string> $options
     *
     * @return list<string>
     */
    private function resolveOptionDeltas(array $options): array
    {
        $add = [];
        $remove = [];
        foreach ($options as $entry) {
            $entry = (string) $entry;
            if ($entry === '') {
                continue;
            }

            if ($entry[0] === '-') {
                $remove[substr($entry, 1)] = true;
                continue;
            }

            $key = $entry[0] === '+' ? substr($entry, 1) : $entry;
            if ($key !== '') {
                $add[$key] ??= true;
            }
        }

        $keys = array_keys(array_diff_key($add, $remove));

        usort($keys, static function (string $a, string $b): int {
            $na = preg_match('/(\d+)$/', $a, $m) ? (int) $m[1] : null;
            $nb = preg_match('/(\d+)$/', $b, $m) ? (int) $m[1] : null;

            return match (true) {
                $na !== null && $nb !== null => $na <=> $nb,
                $na !== null => -1,
                $nb !== null => 1,
                default => 0,
            };
        });

        return $keys;
    }

    /**
     * Ship the bundle's default grid subsystem config (gutter, row-gap,
     * container-padding-x): their steps + per-partial defaults. Projects tweak it
     * in config/config.yml: `options` is additive (+key / -key deltas, resolved in
     * loadExtension), `defaults` merge per key. The subsystems themselves are
     * registered in contao/config/config.php.
     */
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->extension('kiwi_bootstrap', [
            'grid' => [
                'gutter' => [
                    'options' => ['space-0', 'space-2', 'space-4', 'space-6', 'space-8', 'space-12', 'space-20'],
                    'defaults' => [
                        'default' => 'space-6',
                        'main' => 'space-6',
                        'header' => 'space-6',
                        'footer' => 'space-6',
                    ],
                ],
                'row-gap' => [
                    'options' => ['space-0', 'space-2', 'space-4', 'space-6', 'space-8', 'space-12', 'space-20'],
                    'defaults' => [
                        'default' => 'space-0',
                    ],
                ],
                'container-padding-x' => [
                    'options' => ['space-0', 'space-3', 'space-4', 'space-5', 'space-6'],
                    'defaults' => [
                        'default' => 'space-3',
                        'main' => 'space-3',
                        'header' => 'space-3',
                        'footer' => 'space-3',
                    ],
                ],
                'vertical-spacing' => [
                    'options' => ['space-0', 'space-2', 'space-4', 'space-6', 'space-8', 'space-12', 'space-16', 'space-20'],
                    'defaults' => [
                        // The dynamic `default` option: new article spacing fields use it
                        // (→ 3rem), so they follow this config. Content-element groups
                        // default to space-0 (set in the configuration class).
                        'default' => 'space-12',
                    ],
                ],
            ],
        ]);
    }
}
