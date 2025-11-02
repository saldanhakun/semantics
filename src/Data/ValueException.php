<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Data;

use Symfony\Component\Validator\Exception\LogicException;

class ValueException extends LogicException
{
    public const ERR_GENERIC = 1;
    public const ERR_REQUIRED = 2;
    public const ERR_READONLY = 3;
    public const ERR_INVALID_TYPE = 11;
    public const ERR_UNDECLARED_ENUM = 12;
    public const ERR_UNKNOWN_ENUM = 13;
    public const ERR_VALIDATION_MIN = 21;
    public const ERR_VALIDATION_MAX = 22;
    public const ERR_VALIDATION_LENGTH = 23;
    public const ERR_VALIDATION_PATTERN = 24;

    public const DEFAULT_MESSAGES = [
        self::ERR_GENERIC => 'Valor inválido',
        self::ERR_REQUIRED => 'Valor não informado',
        self::ERR_READONLY => 'Valor não pode ser alterado',
        self::ERR_INVALID_TYPE => 'Tipo inválido de valor',
        self::ERR_UNDECLARED_ENUM => '{{ class }}: Opções não declaradas com {{ source }}',
        self::ERR_UNKNOWN_ENUM => '{{ class }}: Valor não permitido: {{ value }}',
        self::ERR_VALIDATION_MIN => 'Valor menor que o mínimo{{ min }} permitido: {{ value }}',
        self::ERR_VALIDATION_MAX => 'Valor maior que o máximo{{ max }} permitido: {{ value }}',
        self::ERR_VALIDATION_LENGTH => 'Valor não possui o tamanho{{ length }} esperado: {{ value }}',
        self::ERR_VALIDATION_PATTERN => 'Valor não possui o padrão{{ pattern }} esperado: {{ value }}',
    ];

    public const MSG_EXPECTED_TYPE = '. Tipo esperado: {{ type }}';
    public const MSG_ACTUAL_TYPE = '. Tipo recebido: {{ type }}';

    public static function createMessage(string $template, array $args): string
    {
        foreach ($args as $key => $value) {
            $template = str_replace("{{ $key }}", $value, $template);
        }

        return $template;
    }

    public static function undeclaredEnumError(string $class, string $source, ?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_UNDECLARED_ENUM], [
            'class' => $class,
            'source' => $source,
        ]);

        return new self($message, self::ERR_UNDECLARED_ENUM, $previous);
    }

    public static function unknownEnumError(string $class, string $value, ?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_UNKNOWN_ENUM], [
            'class' => $class,
            'value' => $value,
        ]);

        return new self($message, self::ERR_UNKNOWN_ENUM, $previous);
    }

    public static function requiredError(?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_REQUIRED], []);

        return new self($message, self::ERR_REQUIRED, $previous);
    }

    public static function readonlyError(?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_READONLY], []);

        return new self($message, self::ERR_READONLY, $previous);
    }

    public static function invalidTypeError(?string $expected, ?string $actual, ?\Throwable $previous = null): self
    {
        $message = [self::createMessage(self::DEFAULT_MESSAGES[self::ERR_INVALID_TYPE], [])];
        if (!empty($expected)) {
            $message[] = self::createMessage(self::MSG_EXPECTED_TYPE, [
                'type' => $expected,
            ]);
        }
        if (!empty($actual)) {
            $message[] = self::createMessage(self::MSG_ACTUAL_TYPE, [
                'type' => $actual,
            ]);
        }

        return new self(implode('', $message), self::ERR_INVALID_TYPE, $previous);
    }

    public static function minError(string $value, ?string $min, ?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_VALIDATION_MIN], [
            'min' => empty($min) ? '' : " ($min)",
            'value' => $value,
        ]);

        return new self($message, self::ERR_VALIDATION_MIN, $previous);
    }

    public static function maxError(string $value, ?string $max, ?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_VALIDATION_MAX], [
            'max' => empty($max) ? '' : " ($max)",
            'value' => $value,
        ]);

        return new self($message, self::ERR_VALIDATION_MAX, $previous);
    }

    public static function lengthError(string $value, ?string $length, ?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_VALIDATION_LENGTH], [
            'length' => empty($length) ? '' : " ($length)",
            'value' => $value,
        ]);

        return new self($message, self::ERR_VALIDATION_LENGTH, $previous);
    }

    public static function patternError(string $value, ?string $pattern, ?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_VALIDATION_PATTERN], [
            'pattern' => empty($pattern) ? '' : " ($pattern)",
            'value' => $value,
        ]);

        return new self($message, self::ERR_VALIDATION_PATTERN, $previous);
    }

}
