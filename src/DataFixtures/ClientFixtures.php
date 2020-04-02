<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Client;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = new Client();
        $data->setLastName('HOCHET');
        $data->setFirstName('Dylan');
        $data->setPhone('0602030405');
        $data->setEmail('dylanhochet@gmail.com');
        $data->setStreet('6 Avenue de Plymouth, porte 1');
        $data->setCity('Cherbourg');
        $data->setPostalCode('50100');
        $manager->persist($data);

        $manager->flush();
    }
}
