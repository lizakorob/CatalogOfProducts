<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * ForgotPassword
 *
 * @ORM\Table(name="forgot_password")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ForgotPasswordRepository")
 */
class ForgotPassword
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="hashCode", type="string", length=255)
     */
    private $hashCode;

    /**
     * @var string
     *
     * @ORM\Column(name="create_date", type="date")
     */
    private $createDate;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ForgotPassword
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set hashCode
     *
     * @param string $hashCode
     *
     * @return ForgotPassword
     */
    public function setHashCode($hashCode)
    {
        $this->hashCode = $hashCode;

        return $this;
    }

    /**
     * Get hashCode
     *
     * @return string
     */
    public function getHashCode()
    {
        return $this->hashCode;
    }

    /**
     * Set createDate
     *
     * @param Date createDate
     *
     * @return ForgotPassword
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
}

