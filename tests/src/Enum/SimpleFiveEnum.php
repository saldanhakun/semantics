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

class SimpleFiveEnum extends BaseEnum
{
    public const OPTIONS = [
        'one' => 'This is One',
        'two' => 'This is Two',
        'three' => 'This is Three',
        'four' => 'This is Four',
        'five' => 'This is Five',
    ];
}
