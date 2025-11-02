<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Tests\src\Enum;

use Saldanhakun\Semantics\Data\Abstract\AbstractEnumValue;

class ProxiedEnumValue extends AbstractEnumValue
{
    protected static function readFromProxy(): array
    {
        return [
            'nine' => 'This is Nine',
            'ten' => 'This is Ten',
        ];
    }
}
