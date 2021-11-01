<?php

declare(strict_types=1);

namespace App\Form\NexusRequestLog;

use App\Entity\NexusRequestLog\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class ResponseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                child: 'completedAt',
                type: DateTimeType::class,
                options: [
                    'html5' => true,
                    'input' => 'datetime_immutable',
                    'model_timezone' => 'UTC',
                    'widget' => 'single_text',
                    'with_minutes' => true,
                    'with_seconds' => true,
                ],
            )
            ->add(
                child: 'headers',
                type: TextareaType::class,
                options: [
                    'constraints' => [
                        new Assert\Json(),
                    ],
                    'label' => 'Headers (JSON)',
                ],
            )
            ->add(
                child: 'statusCode',
                type: IntegerType::class,
                options: [
                    'constraints' => [
                        new Assert\Range(min: 100, max: 599),
                    ],
                ],
            )
            ->add(child: 'statusLine', type: TextType::class)
            ->add(child: 'body', type: TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Response::class,
        ]);
    }
}
