<?php

/*
 * This file is part of the Brazilian Validators package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Enum\Attribute;

trait EnumIconTrait
{
    public function getIcon(bool $asString = true): ?string
    {
        $icons = self::icons();
        if ($icons !== null) {
            return $icons[$this->getKey()];
        }

        return null;
    }

    public static function icons(): ?array
    {
        $list = null;
        if (\defined(static::class . '::ICONS')) {
            $list = static::ICONS;
        }

        return $list;
    }
}
