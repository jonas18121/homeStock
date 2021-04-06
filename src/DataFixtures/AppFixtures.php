<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\StorageSpace;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * faire cette commande pour les envoyers en base de donnée
     * php bin/console doctrine:fixtures:load
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');


        //-------- U S E R -------//

        $user = new User();
        $user->setEmail('mulan@gmail.com')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'mulan'))
            ->setRoles(["ROLE_USER"])
            ->setLastName('mulan')
            ->setFirstName('mulan')
            ->setDateCreatedAt($faker->dateTime())

        ;
        $manager->persist($user);

        //-------- C A T E G O R Y -------//
        $category1 = new Category();
        $category1->setName('Pièces')
        ;
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Garage')
        ;
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName('Box')
        ;
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setName('Hangar')
        ;
        $manager->persist($category4);

        
        //-------- S T O R A G E  S P A C E -------//

        for ($i=1; $i <= 3 ; $i++) { 
            
            $storageSpace = new StorageSpace();

            $storageSpace->setTitle($faker->word())
                ->setDescription($faker->text())
                ->setAdresse($faker->streetAddress())
                ->setCity($faker->city())
                ->setPostalCode($faker->postCode())
                ->setSpace($faker->randomDigit())
                ->setPriceByDays($faker->numberBetween(1, 3) * 100)
                ->setPriceByMonth($faker->numberBetween(20, 50))
                ->setCategory($category2)
                ->setDateCreatedAt($faker->dateTime())
                ->setAvailable(true)
                ->setOwner($user)
            ;

            $manager->persist($storageSpace);
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
