<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Data;

use Saldanhakun\Semantics\Data\Abstract\AbstractStringValue;

class EmailValue extends AbstractStringValue
{

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(): self
    {
        $this->value = null;
        return $this;
    }

    public function getUsername(): ?string
    {
        if (empty($this->value)) {
            return null;
        }
        return explode('@', $this->value)[0];
    }

    public function getDomain(): ?string
    {
        if (empty($this->value)) {
            return null;
        }
        return explode('@', $this->value)[1];
    }

    public function getObfuscated(): ?string
    {
        return $this->value;
    }

    protected function validate(): void
    {
        $this->value = filter_var($this->value, FILTER_SANITIZE_EMAIL|FILTER_VALIDATE_EMAIL);
    }
}
