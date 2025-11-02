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

class InvalidEnumValue extends AbstractEnumValue
{
    public const NOT_OPTIONS = 'the constant name is wrong here';
}
