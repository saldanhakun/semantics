<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Data\Abstract;

use Saldanhakun\Semantics\Data\ValueException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractNumericValue extends AbstractValue
{
    protected float|int|string|null $value;

    protected function getAcceptableTypes(): array
    {
        return ['null', 'integer', 'float', 'string'];
    }

    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        $types = $this->getAcceptableTypes();
        return parent::configureOptions($resolver)
            ->setDefault('default', null)
            ->setAllowedTypes('default', $types)
            ->setDefault('min', null)
            ->setAllowedTypes('min', $types)
            ->setDefault('max', null)
            ->setAllowedTypes('max', $types)
            ;
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }

    protected function setup(): void
    {
        parent::setup();
        if ($this->options['default'] !== null) {
            $this->update($this->options['default']);
        }
    }

    protected function resolve(mixed $value): float|int|string|null
    {
        if (is_float($value) || is_int($value) || is_numeric($value)) {
            return $value;
        }
        elseif (is_null($value)) {
            return null;
        }
        elseif ($value instanceof AbstractNumericValue) {
            return $value->value;
        }
        else {
            throw ValueException::invalidTypeError('Número', null);
        }
    }

    protected function update(mixed $value): void
    {
        $this->value = $this->resolve($value);
        parent::update($value);
    }

    protected function validate(): void
    {
        parent::validate();
        if (!empty($this->options['min']) && !empty($this->value)) {
            $reference = $this->resolve($this->options['min']);
            if ($this->value < $reference) {
                throw ValueException::minError($this->value, $this->options['min']);
            }
        }
        if (!empty($this->options['max']) && !empty($this->value)) {
            $reference = $this->resolve($this->options['max']);
            if ($this->value > $reference) {
                throw ValueException::maxError($this->value, $this->options['max']);
            }
        }
    }

}
