<?php

declare(strict_types=1);

namespace App\Form\NexusRequestLog;

use App\Entity\NexusRequestLog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class MainFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                child: 'request',
                type: RequestFormType::class,
                options: [
                    'constraints' => [
                        new Assert\NotNull(),
                    ],
                ],
            )
            ->add(
                child: 'response',
                type: ResponseFormType::class,
                options: [
                    'constraints' => [
                        new Assert\NotNull(),
                    ],
                ],
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NexusRequestLog::class,
        ]);
    }
}
