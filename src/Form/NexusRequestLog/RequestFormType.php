<?php

declare(strict_types=1);

namespace App\Form\NexusRequestLog;

use App\Entity\NexusRequestLog\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class RequestFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                child: 'id',
                type: TextType::class,
                options: [
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ],
            )
            ->add(child: 'previousId', type: TextType::class)
            ->add(
                child: 'startedAt',
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
                child: 'method',
                type: TextType::class,
                options: [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Choice(['GET', 'POST']),
                    ],
                ],
            )
            ->add(
                child: 'url',
                type: TextType::class,
                options: [
                    'constraints' => [
                        new Assert\Url(protocols: ['http', 'https']),
                    ],
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
                child: 'formData',
                type: TextareaType::class,
                options: [
                    'constraints' => [
                        new Assert\Json(),
                    ],
                    'label' => 'Form data (JSON)',
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
        ]);
    }
}
