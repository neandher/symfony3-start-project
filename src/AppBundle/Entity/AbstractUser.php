<?php

namespace AppBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractUser
 *
 * @ORM\MappedSuperclass()
 * @UniqueEntity(
 *     "email",
 *     message="Esse e-mail ja esta em uso!"
 * )
 */
abstract class AbstractUser
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     * @Assert\Email()
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $emailCanonical;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    /**
     * @param string $emailCanonical
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;
    }

    /**
     * Get the truncated email
     *
     * The default implementation only keeps the part following @ in the address.
     *
     * @return string
     */
    public function getObfuscatedEmail()
    {
        $email = $this->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = '...' . substr($email, $pos);
        }

        return $email;
    }
}