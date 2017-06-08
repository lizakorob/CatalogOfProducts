<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,
     *     max=16,
     *     minMessage="The username should have between 3 and 16 characters.",
     *     maxMessage="The username should have between 3 and 16 characters."
     * )
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     */
    private $username;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=8,
     *     max=64,
     *     minMessage="The password should have between 8 and 64 characters.",
     *     maxMessage="The password should have between 8 and 64 characters."
     * )
     * @ORM\Column(name="password", type="string", length=150)
     */
    private $password;
    /**
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message="The email '{{ value }}' is not a valid email."
     * )
     * @ORM\Column(name="email", type="string", length=60, unique=true)
     */
    private $email;
    /**
     * @Assert\Choice(
     *     choices = { "ROLE_USER", "ROLE_MODERATOR", "ROLE_ADMIN" },
     *     message = "Choose a valid role."
     * )
     * @ORM\Column(name="role", type="string", length=64)
     */
    private $role;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=1,
     *     max=15,
     *     minMessage="The first name should have between 1 and 15 characters."
     * )
     * @ORM\Column(name="first_name", type="string", length=15)
     */
    private $firstName;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=1,
     *     max=20,
     *     minMessage="The surname should have between 1 and 20 characters."
     * )
     * @ORM\Column(name="surname", type="string", length=64)
     */
    private $surname;
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    public function __construct()
    {
        /*$this->firstName = $firstName;
        $this->surname = $surname;
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->email = $email;*/
        $this->role = 'ROLE_USER';
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getRoles()
    {
        return [$this->role];
    }
    public function eraseCredentials()
    {
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }
    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }
    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }
    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }
}