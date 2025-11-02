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
use Saldanhakun\Semantics\Enum\BaseEnum;
use Saldanhakun\Semantics\Enum\EnumException;
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
                $this->context->buildViolation(EnumException::DEFAULT_MESSAGES[EnumException::ERR_UNDECLARED])
                    ->setParameter('{{ class }}', array_pop($baseName))
                    ->setParameter('{{ source }}', $constraint->source)
                    ->addViolation();

                return;
            }
            $choices = \call_user_func($source);
            if ($constraint->lookIntoKeys && !\array_key_exists($value, $choices)) {
                $this->context->buildViolation(EnumException::DEFAULT_MESSAGES[EnumException::ERR_UNKNOWN])
                    ->setParameter('{{ class }}', array_pop($baseName))
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
            } elseif (!$constraint->lookIntoKeys && !\in_array($value, $choices)) {
                $this->context->buildViolation(EnumException::DEFAULT_MESSAGES[EnumException::ERR_UNKNOWN])
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
            throw new EnumException($result->get(0)->getMessage(), EnumException::ERR_GENERIC);
        }
    }

    public static function assert(mixed $value, string $class, bool $required): ?string
    {
        if (empty($value)) {
            if ($required) {
                throw EnumException::requiredError($class);
            }

            return null;
        }
        if ($value instanceof BaseEnum) {
            return $value->getKey();
        }
        $value = \strval($value);
        if (!\array_key_exists($value, \call_user_func([$class, 'all']))) {
            throw EnumException::unknownError($class, $value);
        }

        return $value;
    }
}
