<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

use App\Entity\Software;

class SoftwareFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = new Software();
        $data->setTitle('ADW Cleaner');
        $data->setType('Nettoyage');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('RogueKiller');
        $data->setType('Nettoyage');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('CCleaner');
        $data->setType('Nettoyage');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('JRT');
        $data->setType('Nettoyage');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('ZHP');
        $data->setType('Nettoyage');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('MSE');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('VLC');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Skype');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Chrome');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Open Office');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Avast');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Firefox');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Win Defender');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Super Antispyware');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Opera');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Ninite Win 7');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Ninite Win 8');
        $data->setType('Autre');
        $manager->persist($data);

        $data = new Software();
        $data->setTitle('Ninite Win 10');
        $data->setType('Autre');
        $manager->persist($data);

        $manager->flush();
    }
}
