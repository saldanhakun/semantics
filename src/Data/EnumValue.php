<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Data;

use Saldanhakun\Semantics\Data\Abstract\AbstractEnumValue;

class EnumValue extends AbstractEnumValue
{

    public function getEnumTerm(): mixed
    {
        return $this->value;
    }

    public function getValue(): ?string
    {
        return $this->value?->value;
    }

    public function setValue(mixed $value): static
    {
        $this->update($value);
        return $this;
    }

}
