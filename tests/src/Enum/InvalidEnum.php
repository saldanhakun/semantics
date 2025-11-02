<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Tests\src\Enum;

use Saldanhakun\Semantics\Enum\BaseEnum;

class InvalidEnum extends BaseEnum
{
    public const NOT_OPTIONS = 'the constant name is wrong here';
}
