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
                __DIR__ . '/admin/adminProfile.yml',
                __DIR__ . '/portal/portalProfile.yml',
                __DIR__ . '/user/user.yml',
            ],
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }
}