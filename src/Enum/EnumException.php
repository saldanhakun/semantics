<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Enum;

class EnumException extends \LogicException
{
    public const ERR_GENERIC = 1;
    public const ERR_UNDECLARED = 2;
    public const ERR_UNKNOWN = 3;
    public const ERR_REQUIRED = 4;

    public const DEFAULT_MESSAGES = [
        self::ERR_GENERIC => 'Generic Enum Failure',
        self::ERR_UNDECLARED => '{{ class }}: Opções não declaradas com {{ source }}',
        self::ERR_UNKNOWN => '{{ class }}: Valor não permitido: {{ value }}',
        self::ERR_REQUIRED => 'Valor não informado',
    ];

    public static function createMessage(string $template, array $args): string
    {
        foreach ($args as $key => $value) {
            $template = str_replace("{{ $key }}", $value, $template);
        }

        return $template;
    }

    public static function undeclaredError(string $class, string $source, ?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_UNDECLARED], [
            'class' => $class,
            'source' => $source,
        ]);

        return new self($message, self::ERR_UNDECLARED, $previous);
    }

    public static function unknownError(string $class, string $value, ?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_UNKNOWN], [
            'class' => $class,
            'value' => $value,
        ]);

        return new self($message, self::ERR_UNKNOWN, $previous);
    }

    public static function requiredError(string $class, ?\Throwable $previous = null): self
    {
        $message = self::createMessage(self::DEFAULT_MESSAGES[self::ERR_REQUIRED], [
            'class' => $class,
        ]);

        return new self($message, self::ERR_REQUIRED, $previous);
    }
}
