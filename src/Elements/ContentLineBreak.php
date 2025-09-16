<?php

namespace Kiwi\Contao\BootstrapBundle\Elements;

use Contao\ContentElement;

class ContentLineBreak extends ContentElement
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_bootstrapLinebreak';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        return parent::generate();
    }


    /**
     * Generate the module
     */
    protected function compile()
    {
    }
}
