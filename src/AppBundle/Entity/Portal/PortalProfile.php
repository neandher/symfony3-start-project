<?php

namespace AppBundle\Entity\Portal;

use AppBundle\Entity\AbstractProfile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Portal\PortalProfileRepository")
 * @ORM\Table(name="portal_profile")
 */
class PortalProfile extends AbstractProfile
{
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", mappedBy="portalProfile", cascade={"persist"})
     * @Assert\Valid()
     */
    protected $user;
}