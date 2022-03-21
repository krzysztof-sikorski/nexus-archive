<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\UserAccessToken;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class UserAccessTokenCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserAccessToken::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setSearchFields(['id', 'value'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->setMaxLength(-1)->setDisabled();
        yield TextField::new('value')->setMaxLength(40);
        yield DateTimeField::new('createdAt');
        yield DateTimeField::new('validUntil');
        yield AssociationField::new('owner')->autocomplete();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
            ->add('id')
            ->add('value')
            ->add('createdAt')
            ->add('validUntil')
            ->add('owner');
    }
}
