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

abstract class AbstractDateTimeValue extends AbstractValue
{
    protected ?\DateTime $value;

    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        return parent::configureOptions($resolver)
            ->setDefault('default', null)
            ->setAllowedTypes('default', ['null', 'string', \DateTimeInterface::class])
            ->setDefault('min', null)
            ->setAllowedTypes('min', ['null', 'string', \DateTimeInterface::class])
            ->setDefault('max', null)
            ->setAllowedTypes('max', ['null', 'string', \DateTimeInterface::class])
            ->setDefault('format', 'c')
            ->setAllowedTypes('format', 'string')
            ;
    }

    protected function setup(): void
    {
        parent::setup();
        if ($this->options['default'] !== null) {
            $this->update($this->options['default']);
        }
    }

    public function __toString(): string
    {
        if ($this->value !== null) {
            return $this->value->format($this->options['format']);
        }
        return '';
    }

    protected function resolve(mixed $value): ?\DateTime
    {
        if ($value instanceof \DateTimeInterface) {
            return \DateTime::createFromInterface($value);
        }
        elseif (is_string($value)) {
            return new \DateTime($value);
        }
        elseif (is_null($value)) {
            return null;
        }
        else {
            throw ValueException::invalidTypeError('Date/Time', null);
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
            if ($this->isBefore($reference)) {
                throw ValueException::minError($this->value, $this->options['min']);
            }
        }
        if (!empty($this->options['max']) && !empty($this->value)) {
            $reference = $this->resolve($this->options['max']);
            if ($this->isAfter($reference)) {
                throw ValueException::maxError($this->value, $this->options['max']);
            }
        }
    }

}
