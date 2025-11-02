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

abstract class AbstractValue implements \Stringable
{
    protected array $options;
    abstract public function __toString(): string;

    protected function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        $this->setup();
    }

    protected function setup(): void
    {
        // nop
    }

    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        return $resolver
            ->setDefault('auto_validate', false)
            ->setAllowedTypes('auto_validate', 'bool')
            ->setDefault('required', false)
            ->setAllowedTypes('required', 'bool')
            ;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function isValid(): bool
    {
        try {
            $this->validate();
            return !$this->isEmpty() || !$this->options['required'];
        }
        catch (\Exception $e) {
            return false;
        }
    }

    protected function validate(): void
    {
        if ($this->options['required'] && $this->isEmpty()) {
            throw ValueException::requiredError();
        }
    }

    protected function update(mixed $value): void
    {
        if ($this->options['auto_validate']) {
            $this->validate();
        }
    }
}
