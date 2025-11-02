<?php

/*
 * This file is part of the Semantics package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Tests;

use PHPUnit\Framework\TestCase;
use Saldanhakun\Semantics\Enum\EnumException;
use Saldanhakun\Semantics\Tests\src\Entity\EnrichedEntity;
use Saldanhakun\Semantics\Tests\src\Entity\RawEntity;
use Saldanhakun\Semantics\Tests\src\Entity\TaggedEntity;
use Saldanhakun\Semantics\Tests\src\Enum\InvalidEnum;
use Saldanhakun\Semantics\Tests\src\Enum\ProxiedEnum;
use Saldanhakun\Semantics\Tests\src\Enum\SimpleFiveEnum;
use Saldanhakun\Semantics\Tests\src\Form\DirectForm;
use Saldanhakun\Semantics\Tests\src\Form\EnrichedEntityType;
use Saldanhakun\Semantics\Tests\src\Form\IndirectForm;
use Saldanhakun\Semantics\Tests\src\Form\RawEntityType;
use Saldanhakun\Semantics\Tests\src\Form\TaggedEntityType;
use Saldanhakun\Semantics\Validator\EnumValidator;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\FormFactoryInterface;

class SimpleEnumTest extends TestCase
{
    private FormFactoryInterface $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $factoryBuilder = new FormFactoryBuilder();
        $this->factory = $factoryBuilder->getFormFactory();
    }

    public function testOptions(): void
    {
        $options = SimpleFiveEnum::all();
        $this->assertCount(5, $options);
        $this->assertArrayHasKey('one', $options);
        $this->assertArrayHasKey('two', $options);
        $this->assertArrayHasKey('three', $options);
        $this->assertArrayHasKey('four', $options);
        $this->assertArrayHasKey('five', $options);
        $this->assertSame('This is Three', $options['three']);

        $expanded = SimpleFiveEnum::allWithKeys();
        $this->assertCount(\count($options), $expanded);
        foreach ($expanded as $key => $str) {
            $this->assertArrayHasKey($key, $options);
            $this->assertTrue(str_contains($str, $key) && str_contains($str, $options[$key]));
        }

        $two = SimpleFiveEnum::instanceOrString('two', false);
        $this->assertNotNull($two);
        $this->assertSame('This is Two', $two->getName());
        $this->assertSame('two', $two->getKey());
        $anotherTwo = SimpleFiveEnum::instance('two');
        $this->assertSame($two, $anotherTwo);

        $this->assertSame(
            array_keys(SimpleFiveEnum::all()),
            array_values(SimpleFiveEnum::choices())
        );
    }

    public function testValidation(): void
    {
        $this->assertNull(SimpleFiveEnum::assertOrNull(null));
        $this->assertNull(SimpleFiveEnum::assertOrNull(''));
        $this->assertException(function () {
            SimpleFiveEnum::assert('');
        }, EnumException::ERR_REQUIRED);
        $this->assertNotException(function () {
            SimpleFiveEnum::assert('two');
        });
        $this->assertException(function () {
            SimpleFiveEnum::assert('six');
        }, EnumException::ERR_UNKNOWN);
        $this->assertException(function () {
            InvalidEnum::assert('something');
        }, EnumException::ERR_UNDECLARED);
        $this->assertNotException(function () {
            ProxiedEnum::assert('ten');
        });
    }

    public function testConstraint(): void
    {
        $this->assertNotException(function () {
            EnumValidator::validateOutsideContext(SimpleFiveEnum::all(), 'four', false);
        });
        $this->assertNotException(function () {
            EnumValidator::validateOutsideContext(SimpleFiveEnum::all(), null, false);
            EnumValidator::validateOutsideContext(SimpleFiveEnum::all(), '', false);
        });
        $this->assertException(function () {
            EnumValidator::validateOutsideContext(SimpleFiveEnum::all(), 'four', false);
        }, EnumException::ERR_GENERIC);
    }

    public function testDirectForm(): void
    {
        $builder = $this->factory->createBuilder(DirectForm::class);

        $form = $builder->getForm();
        $form->submit([
            'text' => 'Some text',
            'field' => 'three',
        ]);
        $this->assertTrue($form->isValid());

        $form = $builder->getForm();
        $form->submit([
            'text' => 'Some text',
            'field' => 'seven',
        ]);
        $this->assertFalse($form->isValid());
    }

    public function testIndirectForm(): void
    {
        $builder = $this->factory->createBuilder(IndirectForm::class);

        $form = $builder->getForm();
        $form->submit([
            'text' => 'Some text',
            'field' => 'three',
        ]);
        $this->assertTrue($form->isValid());

        $form = $builder->getForm();
        $form->submit([
            'text' => 'Some text',
            'field' => 'seven',
        ]);
        $this->assertFalse($form->isValid());
    }

    public function testRawEntity(): void
    {
        $entity = new RawEntity();
        $builder = $this->factory->createBuilder(RawEntityType::class, $entity);

        $form = $builder->getForm();
        $form->submit([
            'name' => 'Some name',
            'enum' => 'three',
        ]);
        $this->assertTrue($form->isValid());
        $this->assertSame($entity->enum, 'three');

        $form = $builder->getForm();
        $form->submit([
            'name' => 'Some text',
            'enum' => 'seven',
        ]);
        $this->assertFalse($form->isValid());
    }

    public function testTaggedEntity(): void
    {
        $entity = new TaggedEntity();
        $builder = $this->factory->createBuilder(TaggedEntityType::class, $entity);

        $form = $builder->getForm();
        $form->submit([
            'name' => 'Some name',
            'enum' => 'three',
        ]);
        $this->assertTrue($form->isValid());
        $this->assertSame($entity->enum, 'three');

        $form = $builder->getForm();
        $form->submit([
            'name' => 'Some text',
            'enum' => 'seven',
        ]);
        $this->assertFalse($form->isValid());
    }

    protected function assertException(\Closure $callback, ?int $exceptionCode, string $messageContext = 'EnumException'): void
    {
        try {
            $callback();
            $this->fail(\sprintf('%s: erro esperado não ocorreu', $messageContext));
        } catch (\Exception $error) {
            if ($error instanceof EnumException) {
                $this->assertTrue(
                    $exceptionCode === null || $exceptionCode === $error->getCode(),
                    \sprintf('%s: erro diferente do esperado (%s): %s', $messageContext, \get_class($error), $error->getMessage())
                );
            }
            else {
                $this->fail(\sprintf('%s: erro inesperado (%s): %s', $messageContext, \get_class($error), $error->getMessage()));
            }
        }
    }

    protected function assertNotException(\Closure $callback, string $messageContext = 'EnumException'): void
    {
        try {
            $callback();
            $this->assertTrue(!empty($messageContext));//true
        } catch (\Exception $error) {
            $this->fail(\sprintf('%s: erro inesperado (%s): %s', $messageContext, \get_class($error), $error->getMessage()));
        }
    }
}
