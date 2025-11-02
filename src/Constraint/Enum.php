<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Constraint;

use Saldanhakun\Semantics\Enum\BaseEnum;
use Saldanhakun\Semantics\Validator\EnumValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Marcelo Saldanha <saldanha@uttara.com.br>
 * @license GPL-3.0-or-later
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
class Enum extends Constraint
{
    /**
     * A suitable column length for Enum storage
     */
    public const ORM_LENGTH = 64;

    public const REGEX = '^[_a-z][-a-z_0-9]*$';

    /**
     * @var string Enum Class name
     */
    public string $enumClass = '';

    /**
     * @var string Name of a function on the Enum class that return the acceptable choices
     */
    public string $source = 'all';

    /**
     * @var bool If the values must be checked against the keys, or values, of the choices
     */
    public bool $lookIntoKeys = true;

    #[HasNamedArguments]
    public function __construct(string $enumClass, mixed $options = null, ?array $groups = null, mixed $payload = null)
    {
        if (empty($enumClass)) {
            throw new InvalidArgumentException('Enum class cannot be empty.');
        }
        if (!class_exists($enumClass)) {
            throw new InvalidArgumentException(\sprintf("Enum class '%s' is not declared. Maybe a missing bundle or dependency?", $enumClass));
        }
        if (!is_subclass_of($enumClass, BaseEnum::class, true)) {
            throw new InvalidArgumentException(\sprintf("Enum class '%s' is not an actual BaseEnum descendant.", $enumClass));
        }
        parent::__construct($options, $groups, $payload);
    }

    public function getDefaultOption(): ?string
    {
        return 'enumClass';
    }

    public function getRequiredOptions(): array
    {
        return ['enumClass'];
    }

    /**
     * @inheritdoc
     */
    public function validatedBy(): string
    {
        return EnumValidator::class;
    }
}
