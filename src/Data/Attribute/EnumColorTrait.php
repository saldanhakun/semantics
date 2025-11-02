<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Data\Attribute;

trait EnumColorTrait
{
    public function getColor(): ?string
    {
        $colors = self::colors();
        if ($colors !== null) {
            return $colors[$this->getKey()];
        }

        return null;
    }

    public static function colors(): ?array
    {
        return self::_read_constant('COLORS', []);
    }
}
