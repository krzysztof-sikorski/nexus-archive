<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\NexusRawData;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class NexusRawDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NexusRawData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud(crud: $crud)
            ->setSearchFields(fieldNames: ['id', 'url'])
            ->setDefaultSort(sortFieldsAndOrder: ['submittedAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new(propertyName: 'id')->setMaxLength(length: -1)->setDisabled(disabled: true);
        yield DateTimeField::new(propertyName: 'submittedAt');
        yield AssociationField::new(propertyName: 'submitter')->onlyOnDetail();
        yield DateTimeField::new(propertyName: 'requestStartedAt')->onlyOnDetail();
        yield DateTimeField::new(propertyName: 'responseCompletedAt')->onlyOnDetail();
        yield TextField::new(propertyName: 'method');
        yield TextField::new(propertyName: 'url')->setMaxLength(length: 40);
        yield TextareaField::new(propertyName: 'formData')->onlyOnDetail();
        yield TextEditorField::new(propertyName: 'responseBody')->onlyOnDetail();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters(filters: $filters)
            ->add(propertyNameOrFilter: 'id')
            ->add(propertyNameOrFilter: 'url')
            ->add(propertyNameOrFilter: 'method');
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions(actions: $actions)
            ->add(pageName: Crud::PAGE_INDEX, actionNameOrObject: Action::DETAIL)
            ->disable(
                Action::BATCH_DELETE,
                Action::DELETE,
                Action::EDIT,
                Action::NEW,
                Action::SAVE_AND_ADD_ANOTHER,
                Action::SAVE_AND_CONTINUE,
                Action::SAVE_AND_RETURN
            );
    }
}
