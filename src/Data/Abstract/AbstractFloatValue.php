<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Data\Abstract;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFloatValue extends AbstractNumericValue
{

    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        return parent::configureOptions($resolver)
            ->setDefault('decimals', 2)
            ->setAllowedTypes('decimals', 'integer')
            ->setDefault('as_string', false)
            ->setAllowedTypes('as_string', 'bool')
            ->setDefault('locale', 'pt_BR')
            ->setAllowedTypes('locale', 'string')
            ;
    }

    protected function resolve(mixed $value): float|int|string|null
    {
        $value = parent::resolve($value);
        if ($value !== null) {
            if ($this->options['as_string']) {
                $dec = $this->options['decimals'];
                if ($dec > 0) {
                    return sprintf('%0.' . $dec . 'f', $value);
                }
                else {
                    return sprintf('%d', $value);
                }
            }
            $value = (float) $value;
        }
        return $value;
    }

    public function getFormatted(): string
    {
        if ($this->isEmpty()) {
            return '';
        }
        $formatter = \NumberFormatter::create($this->options['locale'], \NumberFormatter::DECIMAL);
        return $formatter->format($this->value);
    }

}
