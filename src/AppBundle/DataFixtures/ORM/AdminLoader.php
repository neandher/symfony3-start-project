<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class AdminLoader implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $objects = Fixtures::load(
            [
                __DIR__ . '/user.yml',
                __DIR__ . '/admin/adminUser.yml',
            ],
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }
}