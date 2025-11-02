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

abstract class AbstractStringValue extends AbstractValue
{
    protected ?string $value = null;
    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        return parent::configureOptions($resolver)
            ->setDefault('default', null)
            ->setAllowedTypes('default', ['null', 'string'])
            ->setDefault('minLength', null)
            ->setAllowedTypes('minLength', ['null', 'integer'])
            ->setDefault('maxLength', null)
            ->setAllowedTypes('maxLength', ['null', 'integer'])
            ->setDefault('pattern', null)
            ->setAllowedTypes('pattern', ['null', 'string'])
        ;
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }

    protected function update(mixed $value): void
    {
        $this->value = empty($value) ? null : strval($value);
        parent::update($value);
    }

    protected function validate(): void
    {
        parent::validate();
        if (!empty($this->options['minLength'])) {
            $reference = (int) $this->options['minLength'];
            if (mb_strlen($this->value) < $reference) {
                throw ValueException::minError($this->value, $this->options['minLength']);
            }
        }
        if (!empty($this->options['maxLength'])) {
            $reference = (int) $this->options['maxLength'];
            if (mb_strlen($this->value) > $reference) {
                throw ValueException::maxError($this->value, $this->options['maxLength']);
            }
        }
    }

}
