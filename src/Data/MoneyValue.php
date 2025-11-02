<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Data;

use Saldanhakun\Semantics\Data\Abstract\AbstractFloatValue;
use Saldanhakun\Semantics\Data\Abstract\AbstractNumericValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class MoneyValue extends AbstractFloatValue
{

    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        return parent::configureOptions($resolver)
            ->setDefault('as_string', true)
            ->setDefault('currency', 'BRL')
            ->setAllowedTypes('currency', 'string');
    }

    public function getValue(): float|string|null
    {
        return $this->value;
    }

    public function setValue(AbstractNumericValue|float|string|null $value): static
    {
        $this->update($value);
        return $this;
    }

    public function getFormatted(): string
    {
        if ($this->isEmpty()) {
            return '';
        }
        $formatter = \NumberFormatter::create($this->options['locale'], \NumberFormatter::CURRENCY);
        $formatter->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $this->options['currency']);
        return $formatter->format($this->value);
    }

}
