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
        return parent::configureCrud($crud)
            ->setSearchFields(['id', 'url'])
            ->setDefaultSort(['submittedAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->setMaxLength(-1)->setDisabled();
        yield DateTimeField::new('submittedAt');
        yield AssociationField::new('submitter')->onlyOnDetail();
        yield DateTimeField::new('requestStartedAt')->onlyOnDetail();
        yield DateTimeField::new('responseCompletedAt')->onlyOnDetail();
        yield TextField::new('method');
        yield TextField::new('url')->setMaxLength(40);
        yield TextareaField::new('formData')->onlyOnDetail();
        yield TextEditorField::new('responseBody')->onlyOnDetail();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
            ->add('id')
            ->add('url')
            ->add('method');
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
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
