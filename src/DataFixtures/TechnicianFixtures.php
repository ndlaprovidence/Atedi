<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Technician;

class TechnicianFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = new Technician();
        $data->setLastName('HOCHET');
        $data->setFirstName('Dylan');
        $data->setEmail('dylanhochet@gmail.com');
        $manager->persist($data);

        $data = new Technician();
        $data->setLastName('LEYSENNE');
        $data->setFirstName('Remi');
        $data->setEmail('remiley@gmail.com');
        $manager->persist($data);

        $manager->flush();
    }
}
