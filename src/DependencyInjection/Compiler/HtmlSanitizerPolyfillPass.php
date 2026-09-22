<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\DependencyInjection\Compiler;

use Contao\CoreBundle\Framework\ContaoFramework;
use Kiwi\Contao\BootstrapBundle\HtmlSanitizer\ContaoHtmlSanitizer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers a `contao` HTML sanitizer when the installed core does not provide one.
 *
 * The Twig `sanitize_html('<name>')` filter resolves the name through a locator built from every
 * service tagged `html_sanitizer` (`tagged_locator('html_sanitizer', 'sanitizer')`), so a tagged
 * service is all it takes - no framework.html_sanitizer configuration required. contao/core-bundle
 * tags one as `contao` from 5.7 on; below that only Symfony's `default` exists and the templates
 * asking for `contao` fail with a 500.
 *
 * Detection is by tag attribute rather than service id, so any bundle already claiming the name
 * keeps it and this never registers a competing definition.
 *
 * @see ContaoHtmlSanitizer for why the default sanitizer is not a usable substitute.
 */
class HtmlSanitizerPolyfillPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('html_sanitizer') as $arrTags) {
            foreach ($arrTags as $arrTag) {
                if ('contao' === ($arrTag['sanitizer'] ?? null)) {
                    return;
                }
            }
        }

        if (!$container->has(ContaoFramework::class) && !$container->has('contao.framework')) {
            return;
        }

        $definition = new Definition(ContaoHtmlSanitizer::class, [new Reference('contao.framework'), new Reference('contao.insert_tag.parser')]);
        $definition->addTag('html_sanitizer', ['sanitizer' => 'contao']);

        $container->setDefinition('kiwi.contao.bootstrap.html_sanitizer.contao', $definition);
    }
}
