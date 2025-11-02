<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Tests\src\Enum;

use Saldanhakun\Semantics\Data\Abstract\AbstractEnumValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleFiveEnumValue extends AbstractEnumValue
{
    public function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        return parent::configureOptions($resolver)
            ->setRequired('enum')
            ->setAllowedTypes('enum', ['enum']);
    }

}

enum SimpleFiveEnum {
    case one;
    case two;
    case three;
    case four;
    case five;

    public function label(): string
    {
        return match ($this) {
            self::one => 'This is One',
            self::two => 'This is Two',
            self::three => 'This is Three',
            self::four => 'This is Four',
            self::five => 'This is Five',
        };
    }
}