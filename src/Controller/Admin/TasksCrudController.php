<?php

namespace App\Controller\Admin;

use App\Entity\Tasks;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Filter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use function Sodium\add;

class TasksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tasks::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User Task')
            ->setEntityLabelInPlural('Users Tasks')
            ->setSearchFields(['userId', 'title', 'createdDate'])
            ->setDefaultSort(['createdDate' => 'ASC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('userId')
            ->add('title')
            ->add(ChoiceFilter::new('status')->setChoices([
                'Active' => Tasks::STATUS_ACTIVE,
                'Completed' => Tasks::STATUS_COMPLETED,
                'Overdue' => Tasks::STATUS_OVERDUE,
            ]));
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('userId')->setLabel('name');
        yield TextField::new('title');

        yield TextField::new('description')->hideOnIndex();
        yield ChoiceField::new('status')
            ->setChoices([
                'Active' => Tasks::STATUS_ACTIVE,
                'Completed' => Tasks::STATUS_COMPLETED,
                'Overdue' => Tasks::STATUS_OVERDUE,
            ]);

        if(Crud::PAGE_EDIT === $pageName){
            yield DateTimeField::new('createdDate')->onlyOnIndex();
        }else{
            yield DateTimeField::new('createdDate')->setFormTypeOption('disabled', true);
        }
    }

}
