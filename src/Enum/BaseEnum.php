<?php

/*
 * This file is part of the Brazilian Validators package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Enum;

use LogicException;
use Saldanhakun\Semantics\Validator\EnumValidator;

abstract class BaseEnum
{
    /***
     * No mínimo, a classe precisa definir a lista de opções:
     * public const array OPTIONS = [ 'str1' => "String 1", ...];
     */

    protected static function _read_constant(string $constantName, mixed $default = null): mixed
    {
        $constName = \sprintf('%s::%s', static::class, strtoupper($constantName));
        if (\defined($constName)) {
            return \constant($constName);
        }

        return $default;
    }

    protected static function readFromProxy(): array
    {
        throw EnumException::undeclaredError(static::class, 'readFromProxy');
    }

    /**
     * @return array A lista associativa de termos válidos (key => text)
     */
    final public static function all(): array
    {
        $keys = self::_read_constant('OPTIONS');
        if (empty($keys)) {
            $keys = self::readFromProxy();
        }

        return $keys;
    }

    final public static function alphabetical(): array
    {
        $options = self::all();
        asort($options);

        return $options;
    }

    /** @return BaseEnum[] */
    final public static function allInstances(bool $alphabetical): array
    {
        $instances = $alphabetical ? self::alphabetical() : self::all();
        foreach ($instances as $key => $name) {
            $instances[$key] = self::instance($key);
        }

        return $instances;
    }

    /**
     * @return array A lista associativa, mas com texto incluindo a chave
     */
    final public static function allWithKeys(): array
    {
        $data = [];
        foreach (self::all() as $key => $text) {
            $data[$key] = self::format($key, $text);
        }

        return $data;
    }

    /**
     * Formata uma descrição conjunta com chave e texto
     * @param $key
     * @param $text
     * @return string
     */
    protected static function format($key, $text): string
    {
        return \sprintf('%s - %s', $key, $text);
    }

    /**
     * @return array A lista das chaves disponíveis (calculada a partir de all())
     */
    final public static function keys(): array
    {
        return array_keys(self::all());
    }

    /**
     * O texto equivalente de uma chave qualquer
     * @param string $key Será validada
     * @return string
     * @throws LogicException
     */
    final public static function describe(string $key): string
    {
        $keys = self::all();
        $valid = EnumValidator::assert(static::class, $key, true);

        return $keys[$valid];
    }

    /**
     * @return array A lista de valores no formato esperado por ChoiceType (text => key)
     */
    final public static function choices(): array
    {
        return array_flip(self::all());
    }

    /**
     * @return array A lista de valores no formato esperado por ChoiceType (text => key)
     */
    final public static function alphabeticalChoices(): array
    {
        return array_flip(self::alphabetical());
    }

    /**
     * @return array A lista de valores no formato esperado por ChoiceType (text => key)
     */
    public static function choicesWithKeys(): array
    {
        return array_flip(self::allWithKeys());
    }

    private static array $cache = [];

    /**
     * Inicializa (globalmente) um valor qualquer das opções.
     * Construtor protegido para evitar instanciação fora do controle do cache
     * @param string $key
     * @param string $name
     */
    protected function __construct(private readonly string $key, private readonly string $name)
    {
    }

    /**
     * Retorna um valor instanciado para a chave (cache global automático)
     * @param string $key
     * @return self
     */
    final public static function instance(string $key): self
    {
        if (!\array_key_exists($key, self::$cache)) {
            self::$cache[$key] = new static($key, self::describe($key));
        }

        return self::$cache[$key];
    }

    /**
     * Retorna uma instância ou a própria chave, conforme desejado. Útil para implementar get() flexíveis na Entidade.
     * @param string|null $key
     * @param bool $asString
     * @return self|string|null
     */
    final public static function instanceOrString(?string $key, bool $asString): static|string|null
    {
        $valid = EnumValidator::assert(static::class, $key, false);
        if ($valid) {
            return self::instance($valid);
        }
        return null;
    }

    /**
     * Garante que o valor é válido para atribuição, seja uma string ou uma instância
     * @param mixed $value
     * @return string
     */
    final public static function assert(mixed $value): string
    {
        return EnumValidator::assert(static::class, $value, true);
    }

    final public static function assertOrNull($value): ?string
    {
        return EnumValidator::assert(static::class, $value, false);
    }

    final public static function assertArray(?array $list, bool $allowNull): array
    {
        foreach ($list as $index => $key) {
            if ($allowNull) {
                $list[$index] = self::assertOrNull($key);
            } else {
                $list[$index] = self::assert($key);
            }
        }
        $list = array_unique($list);
        if (empty($list) && !$allowNull) {
            throw EnumException::requiredError(static::class);
        }

        return $list;
    }

    /**
     * Checks if value is expected by the enum
     * @param string $key
     * @return bool
     */
    final public static function isValid(string $key): bool
    {
        try {
            EnumValidator::assert(static::class, $key, true);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

}
