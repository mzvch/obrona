<?php
declare (strict_types = 1);

namespace App\Entity;

use App\Repository\PocketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table (name="pocket")
 * @ORM\Entity (repositoryClass=PocketRepository::class)
 */
class Pocket
{
    /* CLASS FIELDS */

    /**
     * @ORM\Id ()
     * @ORM\GeneratedValue (strategy="AUTO")
     * @ORM\Column (name="id", type="integer")
     */
    private int $id;

    /**
     * @ORM\Column (name="name", type="string", length=30)
     * @Assert\NotBlank (message="pocket_name_not_blank")
     * @Assert\Length (max="30", maxMessage="pocket_name_max_length")
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne (targetEntity=User::class, inversedBy="pockets")
     * @ORM\JoinColumn (nullable=false)
     */
    private ?UserInterface $user;

    /**
     * @ORM\Column (name="account_balance", type="decimal", precision=10, scale=2)
     * @Assert\GreaterThanOrEqual (value="0", message="pocket_account_balance_greater_than_or_equal")
     */
    private ?float $accountBalance;

    /**
     * @ORM\OneToMany (targetEntity=FinancialTransaction::class, mappedBy="pocket", orphanRemoval=true)
     */
    private Collection $financialTransactions;


    /* CONSTRUCTORS */

    public function __construct ()
    {
        $this -> financialTransactions = new ArrayCollection ();
    }


    /* GETTERS AND SETTERS */

    public function getId () : int
    {
        return $this -> id;
    }

    public function getName () : ?string
    {
        return $this -> name;
    }

    public function setName (?string $name) : self
    {
        $this -> name = $name;

        return $this;
    }

    public function getUser () : ?UserInterface
    {
        return $this -> user;
    }

    public function setUser (?UserInterface $user) : self
    {
        $this -> user = $user;

        return $this;
    }

    public function getAccountBalance () : ?float
    {
        return $this -> accountBalance;
    }

    public function setAccountBalance (?float $accountBalance) : self
    {
        $this -> accountBalance = $accountBalance;

        return $this;
    }

    public function getFinancialTransactions () : Collection
    {
        return $this -> financialTransactions;
    }


    /* CLASS FIELDS */

    public function addFinancialTransaction (FinancialTransaction $financialTransaction) : self
    {
        if (!$this -> financialTransactions -> contains ($financialTransaction))
        {
            $this -> financialTransactions [] = $financialTransaction;
            $financialTransaction -> setPocket ($this);
        }

        return $this;
    }
}