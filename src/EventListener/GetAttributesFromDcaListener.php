<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;

/**
 * @Hook("getAttributesFromDca")
 */
class GetAttributesFromDcaListener
{
    public function __invoke(array $attributes, $context = null)
    {
        if (array_key_exists('onclick', $attributes)) {
            if ($context->field == 'overwriteDefaultColumns') {
                // turn the ajax subpalette loading into a generic submitOnChange, to update the container preview upon showing / hiding the subpalette
                $attributes['onclick'] = "Backend.autoSubmit('" . $context->table . "')";
            }
        }

        return $attributes;
    }
}
