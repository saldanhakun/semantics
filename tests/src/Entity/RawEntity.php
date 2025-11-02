<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Tests\src\Entity;

use Saldanhakun\Semantics\Tests\src\Enum\SimpleFiveEnum;

class RawEntity
{
    public string $name = '';
    public string $enum = 'one';

    public function getRaw(): string
    {
        return \sprintf('My name is %s and %s', $this->name, $this->enum);
    }

    public function getExpanded(): string
    {
        $enum = SimpleFiveEnum::instance($this->enum);

        return \sprintf('My name is %s and %s', $this->name, $enum->getName());
    }
}
