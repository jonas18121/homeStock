<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BookingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('lodger', 'Locataire'),
            TextField::new('storageSpace', 'Espace de stokage'),
            DateTimeField::new('dateCreatedAt', 'Date de création'),
            DateField::new('dateStartAt', 'Date de début'),
            DateField::new('dateEndAt', 'Date de Fin'),
        ];
    }
    
}
