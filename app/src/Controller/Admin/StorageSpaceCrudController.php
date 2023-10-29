<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\StorageSpace;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            TextareaField::new('description'),
            IntegerField::new('space', 'Espace en m2'),
            MoneyField::new('priceByDays', 'Prix par jours')->setCurrency('EUR'),
            MoneyField::new('priceByMonth', 'Prix par mois')->setCurrency('EUR'),
            AssociationField::new('category', 'Categorie')->setRequired(true),
            TextField::new('adresse', 'Adresse'),
            TextField::new('postalCode', 'Code postal'),
            TextField::new('city', 'Ville'),
            BooleanField::new('available', 'Etat'),
            DateTimeField::new('createdAt', 'Date de création'),
            AssociationField::new('owner', 'Propriétaire')->setRequired(true),
            ImageField::new('images', 'Image')
                ->setBasePath('uploads/images')
                ->setUploadDir('public/uploads/images')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
        ];
    }
}
