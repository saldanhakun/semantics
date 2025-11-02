<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Tests\src\Form;

use Saldanhakun\Semantics\Form\EnumFormType;
use Saldanhakun\Semantics\Tests\src\Enum\SimpleFiveEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DirectForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('text', TextType::class, [
            'label' => 'Text',
            'required' => false,
        ]);
        $builder->add('field', EnumFormType::class, [
            'label' => 'Enum Field',
            'required' => true,
            'enum_class' => SimpleFiveEnum::class,
        ]);
    }
}
