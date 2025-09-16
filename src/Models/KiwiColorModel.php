<?php

namespace Kiwi\Contao\BootstrapBundle\Models;

use Contao\Model;

/**
 * @property string $title
 * @property string $variable
 * @property string $value
 */
class KiwiColorModel extends Model
{
    protected static $strTable = "tl_kiwi_color";
}
