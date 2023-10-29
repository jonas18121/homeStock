<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateCategoryCommand extends Command
{
    protected static $defaultName = 'create:category';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Nom de la catégory')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string */
        $name = $input->getOption('name');

        // instanciez la classe SymfonyStyle
        $io = new SymfonyStyle($input, $output);

        $category = new Category();
        $category->setName($name);

        $this->em->persist($category);
        $this->em->flush();

        // permet d'afficher dans le terminal
        $output->write('La catégorie à bien été créer !');

        // permet d'afficher dans le terminal avec du style
        $io->success('La catégorie à bien été créer !');

        return 0;
    }
}
