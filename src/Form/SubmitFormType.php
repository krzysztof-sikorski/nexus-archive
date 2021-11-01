<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\NexusRequestLog\MainFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class SubmitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'userAccessToken', type: TextType::class)
            ->add(child: 'nexusRequestLog', type: MainFormType::class);
    }
}
