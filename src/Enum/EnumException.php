<?php

/*
 * This file is part of the Brazilian Validators package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Enum;

class EnumException extends \LogicException
{
    public const ERR_PROPERTIES = 1;

    public function isPropertiesError(): bool
    {
        return $this->getCode() == self::ERR_PROPERTIES;
    }
}
