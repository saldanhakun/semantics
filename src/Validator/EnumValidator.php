<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Validator;

use Saldanhakun\Semantics\Constraint\Enum;
use Saldanhakun\Semantics\Data\Abstract\AbstractEnumValue;
use Saldanhakun\Semantics\Data\ValueException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Validation;

class EnumValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($constraint instanceof Enum && !empty($value)) {
            $source = [$constraint->enumClass, $constraint->source];
            $baseName = explode('\\', $constraint->enumClass);
            if (!\is_callable($source)) {
                $this->context->buildViolation(ValueException::DEFAULT_MESSAGES[ValueException::ERR_UNDECLARED_ENUM])
                    ->setParameter('{{ class }}', array_pop($baseName))
                    ->setParameter('{{ source }}', $constraint->source)
                    ->addViolation();

                return;
            }
            $choices = \call_user_func($source);
            if ($constraint->lookIntoKeys && !\array_key_exists($value, $choices)) {
                $this->context->buildViolation(ValueException::DEFAULT_MESSAGES[ValueException::ERR_UNKNOWN_ENUM])
                    ->setParameter('{{ class }}', array_pop($baseName))
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
            } elseif (!$constraint->lookIntoKeys && !\in_array($value, $choices)) {
                $this->context->buildViolation(ValueException::DEFAULT_MESSAGES[ValueException::ERR_UNKNOWN_ENUM])
                    ->setParameter('{{ class }}', array_pop($baseName))
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
            }
        }
    }

    public static function validateOutsideContext(array $acceptable, mixed $value, bool $required): void
    {
        if (empty($value) && !$required) {
            return;
        }
        $context = Validation::createValidator();
        $constraints = [new Choice([], array_flip($acceptable))];
        if ($required) {
            $constraints[] = new Required();
        }
        $result = $context->validate($value, $constraints);
        if ($result->count() > 0) {
            throw new ValueException($result->get(0)->getMessage(), ValueException::ERR_GENERIC);
        }
    }

    public static function assert(mixed $value, string $class, bool $required): ?string
    {
        if (empty($value)) {
            if ($required) {
                throw ValueException::requiredError();
            }

            return null;
        }
        if ($value instanceof AbstractEnumValue) {
            return $value->getKey();
        }
        $value = \strval($value);
        if (!\array_key_exists($value, \call_user_func([$class, 'all']))) {
            throw ValueException::unknownEnumError($class, $value);
        }

        return $value;
    }
}
