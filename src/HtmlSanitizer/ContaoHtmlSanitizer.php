<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\HtmlSanitizer;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\Input;
use Contao\InputEncodingMode;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

/**
 * Back-port of Contao's own `contao` HTML sanitizer for installations below the core version
 * that ships it.
 *
 * The form templates use the canonical Contao idiom
 *
 *     {{ option.label|sanitize_html('contao')|insert_tag_raw }}
 *
 * taken from core. The argument names a sanitizer service, and `contao` is registered by
 * contao/core-bundle only from 5.7 on ({@see \Contao\CoreBundle\HtmlSanitizer\ContaoHtmlSanitizer}).
 * This bundle supports ^5.3, where the Twig sanitizer locator knows `default` alone - and the
 * default sanitizer is the wrong tool twice over: it encodes insert-tag braces, so the following
 * `insert_tag_raw` no longer matches them and tags render as literal text, and it strips `href`
 * from links, so a privacy-policy link in a consent label silently stops working. Asking for the
 * missing service instead throws, taking the whole page down with a 500.
 *
 * So supply it where core does not. The implementation is core's, verbatim: `Input::encodeInput()`
 * in `sanitizeHtml` mode with insert-tag encoding switched off - both the mode and that argument
 * exist unchanged since 5.3, so this is a true back-port rather than an approximation.
 *
 * One deviation from core's version, forced by the surrounding pipeline: insert tags are resolved
 * here. Core's Twig runtime post-processes every sanitized value with `Input::encodeInput()`,
 * whose `$encodeInsertTags` argument defaults to true, so `{{link_open::12}}` would come back as
 * `&#123;&#123;link_open::12&#125;&#125;` and the `insert_tag_raw` that follows in the template
 * would have nothing left to match - a privacy-policy link in a consent label would render as
 * visible markup. The version shipping this sanitizer returns early from that runtime instead,
 * which cannot be back-ported: the runtime is resolved by class name, so replacing it unregisters
 * it. Resolving the tags before returning leaves no braces for the post-encoding to touch, and the
 * template's own `insert_tag_raw` then finds nothing to do. Order of operations matches core -
 * there too the tags are expanded after sanitizing, so their output is not sanitized either.
 *
 * Registered by {@see \Kiwi\Contao\BootstrapBundle\DependencyInjection\Compiler\HtmlSanitizerPolyfillPass}
 * only when nothing else claims the `contao` sanitizer name, so on 5.7+ core's own service keeps
 * the field and this class is never instantiated. Remove both once the bundle requires a core
 * version that ships them.
 */
class ContaoHtmlSanitizer implements HtmlSanitizerInterface
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly InsertTagParser $insertTagParser,
    ) {
    }

    public function sanitize(string $input): string
    {
        $this->framework->initialize();

        return $this->insertTagParser->replace(
            Input::encodeInput($input, InputEncodingMode::sanitizeHtml, false),
        );
    }

    public function sanitizeFor(string $element, string $input): string
    {
        return $this->sanitize($input);
    }
}
