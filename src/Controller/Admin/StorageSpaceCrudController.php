<?php

namespace App\Controller\Admin;

use App\Entity\StorageSpace;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class StorageSpaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StorageSpace::class;
    }

    
     public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('title', 'Titre'),
            TextEditorField::new('description'),
            IntegerField::new('space', 'Espace en m2'),
            MoneyField::new('priceByDays', 'Prix par jours')->setCurrency('EUR'),
            MoneyField::new('priceByMonth', 'Prix par mois')->setCurrency('EUR'),
            AssociationField::new('category', 'Categorie')->setRequired(true),
            TextField::new('adresse', 'Adresse'),
            TextField::new('postalCode', 'Code postal'),
            TextField::new('city', 'Ville'),
            BooleanField::new('available'),
            DateTimeField::new('dateCreatedAt', 'date de création'),
            AssociationField::new('owner', 'Propriétaire de cette espace de stokage')->setRequired(true),
            ImageField::new('images', 'Image')
                ->setBasePath('uploads/images')
                ->setUploadDir('public/uploads/images')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
        ]; 
    } 
    
}
