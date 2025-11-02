<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Data\Abstract;

abstract class AbstractIntegerValue extends AbstractNumericValue
{

    protected function resolve(mixed $value): float|int|string|null
    {
        $value = parent::resolve($value);
        if ($value !== null) {
            $value = (int) $value;
        }
        return $value;
    }

}
