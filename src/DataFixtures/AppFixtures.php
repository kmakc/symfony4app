<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $microPost = new MicroPost();
            $microPost->setText('some random text ' . rand(0,100));
            $microPost->setTime(new \DateTime(date("Y-m-d H:i:s", mt_rand(1262055681, time()))));
            $manager->persist($microPost);
        }

        $manager->flush();
    }
}
