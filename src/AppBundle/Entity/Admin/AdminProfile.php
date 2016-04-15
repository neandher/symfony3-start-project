<?php

namespace AppBundle\Entity\Admin;

use AppBundle\Entity\AbstractProfile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Admin\AdminProfileRepository")
 * @ORM\Table(name="admin_profile")
 */
class AdminProfile extends AbstractProfile
{
    public static $NUM_ITEMS = 5;
}