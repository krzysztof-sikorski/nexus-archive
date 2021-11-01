<?php

declare(strict_types=1);

namespace App\Form\NexusRequestLog;

use App\Entity\NexusRequestLog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UuidType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MainFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'id', type: UuidType::class, options: ['disabled' => true])
            ->add(child: 'request', type: RequestFormType::class)
            ->add(child: 'response', type: ResponseFormType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NexusRequestLog::class,
        ]);
    }
}
