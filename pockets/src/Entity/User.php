<?php
declare (strict_types = 1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table (name="user")
 * @ORM\Entity (repositoryClass=UserRepository::class)
 * @UniqueEntity (fields={"email"}, message="user_email_unique")
 */
class User implements UserInterface
{
    /* CLASS FIELDS */

    const ROLE_ADMIN = "ROLE_ADMIN";

    /**
     * @ORM\Id ()
     * @ORM\GeneratedValue (strategy="AUTO")
     * @ORM\Column (name="id", type="integer")
     */
    private int $id;

    /**
     * @ORM\Column (name="first_name", type="string", length=30)
     * @Assert\NotBlank (message="user_first_name_not_blank")
     * @Assert\Length (max="30", maxMessage="user_first_name_max_length")
     */
    private ?string $firstName;

    /**
     * @ORM\Column (name="last_name", type="string", length=50)
     * @Assert\NotBlank (message="user_last_name_not_blank")
     * @Assert\Length (max="50", maxMessage="user_last_name_max_length")
     */
    private ?string $lastName;

    /**
     * @ORM\Column (name="email", type="string", length=50, unique=true)
     * @Assert\NotBlank (message="user_email_not_blank")
     * @Assert\Length (max="50", maxMessage="user_email_max_length")
     * @Assert\Email (message="user_email_format")
     */
    private string $email;

    /**
     * @ORM\Column (name="password", type="string")
     */
    private string $password;

    /**
     * @ORM\OneToMany (targetEntity=Pocket::class, mappedBy="user", orphanRemoval=true)
     */
    private Collection $pockets;

    /**
     * @ORM\Column (name="roles", type="json")
     */
    private array $roles = [];


    /* CONSTRUCTOR */

    public function __construct ()
    {
        $this -> pockets = new ArrayCollection ();
    }


    /* GETTERS AND SETTERS */

    public function getId () : int
    {
        return $this -> id;
    }

    public function getFirstName () : string
    {
        return $this -> firstName;
    }

    public function setFirstName (string $firstName = null) : self
    {
        $this -> firstName = $firstName;

        return $this;
    }

    public function getLastName () : string
    {
        return $this -> lastName;
    }

    public function setLastName (string $lastName = null) : self
    {
        $this -> lastName = $lastName;

        return $this;
    }

    public function getEmail () : string
    {
        return $this -> email;
    }

    public function setEmail (string $email) : self
    {
        $this -> email = $email;

        return $this;
    }

    public function getPassword () : string
    {
        return $this -> password;
    }

    public function setPassword (string $password) : self
    {
        $this -> password = $password;

        return $this;
    }

    public function getPockets () : Collection
    {
        return $this -> pockets;
    }

    public function getRoles () : array
    {
        $roles = $this -> roles;
        $roles [] = "ROLE_USER";

        return array_unique ($roles);
    }

    public function setRoles (array $roles) : self
    {
        $this -> roles = $roles;

        return $this;
    }


    /* METHODS */

    public function getSalt () {}

    public function eraseCredentials () {}

    public function getUsername () : string
    {
        return $this -> email;
    }

    public function addPocket (Pocket $pocket) : self
    {
        if (!$this -> pockets -> contains ($pocket))
        {
            $this -> pockets [] = $pocket;
            $pocket -> setUser ($this);
        }

        return $this;
    }
}