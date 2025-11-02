<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Data\Abstract;

use LogicException;
use Saldanhakun\Semantics\Data\ValueException;
use Saldanhakun\Semantics\Validator\EnumValidator;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractEnumValue extends AbstractValue
{

    protected mixed $value;

    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        return parent::configureOptions($resolver)
            ->setDefault('available_terms', null)
            ->setAllowedTypes('available_terms', ['null', 'array']);
    }

    /**
     * @return array A lista associativa de termos válidos (key => text)
     */
    final public static function all(): array
    {
        $keys = self::_read_constant('OPTIONS');
        if (empty($keys)) {
            $keys = static::readFromProxy();
        }

        return $keys;
    }

    final public static function alphabetical(): array
    {
        $options = self::all();
        asort($options);

        return $options;
    }

    /** @return AbstractEnumValue[] */
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
        $valid = EnumValidator::assert($key, static::class, true);

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
    final protected function __construct(private readonly string $key, private readonly string $name, array $options=[])
    {
        parent::__construct($options);
    }

    /**
     * Retorna um valor instanciado para a chave (cache global automático)
     * @param string $key
     * @return static
     */
    final public static function instance(string $key): static
    {
        if (!\array_key_exists($key, static::$cache)) {
            self::$cache[$key] = new static($key, self::describe($key));
        }

        return self::$cache[$key];
    }

    /**
     * Retorna uma instância ou a própria chave, conforme desejado. Útil para implementar get() flexíveis na Entidade.
     * @param string|null $key
     * @param bool $asString
     * @return static|string|null
     */
    final public static function instanceOrString(?string $key, bool $asString): static|string|null
    {
        $valid = EnumValidator::assert($key, static::class, false);
        if ($valid) {
            if ($asString) {
                return $valid;
            }
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
        return EnumValidator::assert($value, static::class, true);
    }

    final public static function assertOrNull($value): ?string
    {
        return EnumValidator::assert($value, static::class, false);
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
            throw ValueException::requiredError(static::class);
        }

        return $list;
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

    protected function validate(): void
    {
        EnumValidator::assert($this->key, static::class, false);
    }
}
