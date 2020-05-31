<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Equipment;

class EquipmentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = new Equipment();
        $data->setTitle('PC Portable');
        $manager->persist($data);

        $data = new Equipment();
        $data->setTitle('PC Fixe');
        $manager->persist($data);

        $manager->flush();
    }
}
