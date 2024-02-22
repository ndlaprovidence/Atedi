<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Props;

class PropsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = new Props();
        $data->setTitle('Sacoche');
        $manager->persist($data);

        $data = new Props();
        $data->setTitle('Chargeur');
        $manager->persist($data);

        $manager->flush();
    }
}
