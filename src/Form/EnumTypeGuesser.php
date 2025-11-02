<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Form;

use Saldanhakun\Semantics\Constraint\Enum;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess;

class EnumTypeGuesser implements FormTypeGuesserInterface
{
    private bool $attributesLoaded = false;
    /**
     * @var \ReflectionAttribute[]
     */
    private array $attributes;

    public function guessType(string $class, string $property): ?Guess\TypeGuess
    {
        foreach ($this->getAttributes($class, $property) as $attr) {
            if ($attr->getName() == Enum::class) {
                $args = $attr->getArguments();
                if (!empty($args['enumClass']) || \count($args) == 1) {
                    $options = [
                        'enum_class' => $args['enumClass'] ?? array_pop($args),
                    ];
                    $source = [$args['enumClass'], $args['source']];
                    $choices = \call_user_func($source);
                    if (!empty($args['lookIntoKeys'])) {
                        $choices = array_flip($choices);
                    }
                    $options['choices'] = $choices;

                    return new Guess\TypeGuess(EnumFormType::class, $options, Guess\Guess::HIGH_CONFIDENCE);
                }
            }
        }

        return null;
    }

    public function guessRequired(string $class, string $property): ?Guess\ValueGuess
    {
        return null;
    }

    public function guessMaxLength(string $class, string $property): ?Guess\ValueGuess
    {
        return new Guess\ValueGuess(Enum::ORM_LENGTH, Guess\Guess::HIGH_CONFIDENCE);
    }

    public function guessPattern(string $class, string $property): ?Guess\ValueGuess
    {
        return new Guess\ValueGuess(Enum::REGEX, Guess\Guess::HIGH_CONFIDENCE);
    }

    protected function getAttributes(string $class, string $property): array
    {
        if (!$this->attributesLoaded) {
            $reflection = new \ReflectionProperty($class, $property);
            $this->attributes = $reflection->getAttributes();
            $this->attributesLoaded = true;
        }

        return $this->attributes;
    }
}
