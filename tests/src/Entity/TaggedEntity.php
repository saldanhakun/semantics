<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Tests\src\Entity;

use Saldanhakun\Semantics\Constraint\Enum;
use Saldanhakun\Semantics\Tests\src\Enum\SimpleFiveEnumValue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Contracts\Service\Attribute\Required;

class TaggedEntity
{
    #[Length(min: 5, max: 100)]
    #[Required]
    public string $name = '';

    #[Enum(SimpleFiveEnumValue::class)]
    #[Required]
    public string $enum = 'one';

    public function getRaw(): string
    {
        return \sprintf('My name is %s and %s', $this->name, $this->enum);
    }

    public function getExpanded(): string
    {
        $enum = SimpleFiveEnumValue::instance($this->enum);

        return \sprintf('My name is %s and %s', $this->name, $enum->getName());
    }
}
