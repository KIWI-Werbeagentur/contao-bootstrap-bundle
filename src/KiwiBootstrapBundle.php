<?php

namespace Kiwi\Contao\BootstrapBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KiwiBootstrapBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
