<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Task;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = new Task();
        $data->setTitle('Réparation');
        $manager->persist($data);

        $data = new Task();
        $data->setTitle('Formatage');
        $manager->persist($data);

        $data = new Task();
        $data->setTitle('Nettoyage');
        $manager->persist($data);

        $manager->flush();
    }
}
