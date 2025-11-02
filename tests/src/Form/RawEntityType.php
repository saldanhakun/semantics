<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Tests\src\Form;

use Saldanhakun\Semantics\Tests\src\Entity\RawEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RawEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('name');
        $builder->add('enum');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', RawEntity::class);
    }
}
