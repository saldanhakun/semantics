<?php

/*
 * This file is part of the Brazilian Validators package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Enum\Attribute;

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
        $list = null;
        if (\defined(static::class . '::COLORS')) {
            $list = static::COLORS;
        }

        return $list;
    }
}
