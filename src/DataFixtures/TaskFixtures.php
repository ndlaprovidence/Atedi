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
        $data->setColor('BE2200');
        $manager->persist($data);

        $data = new Task();
        $data->setTitle('Formatage');
        $data->setColor('46BF15');
        $manager->persist($data);

        $data = new Task();
        $data->setTitle('Nettoyage');
        $data->setColor('157CBF');
        $manager->persist($data);

        $data = new Task();
        $data->setTitle('Mise à jour drivers');
        $data->setColor('9315BF');
        $manager->persist($data);

        $manager->flush();
    }
}
