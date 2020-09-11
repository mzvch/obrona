<?php
declare (strict_types = 1);

namespace App\Entity;

use App\Repository\FinancialTransactionRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity (repositoryClass=FinancialTransactionRepository::class)
 */
class FinancialTransaction
{
    /* CLASS FIELDS */

    /**
     * @ORM\Id ()
     * @ORM\GeneratedValue (strategy="AUTO")
     * @ORM\Column (name="id", type="integer")
     */
    private int $id;

    /**
     * @ORM\Column (name="title", type="string", length=30)
     * @Assert\NotBlank (message="financial_transaction_title_not_blank")
     * @Assert\Length (max="30", maxMessage="financial_transaction_title_max_length")
     */
    private string $title;

    /**
     * @ORM\Column (name="amount", type="decimal", precision=10, scale=2)
     * @Assert\NotBlank (message="financial_transaction_amount_not_blank")
     * @Assert\NotEqualTo (value="0", message="financial_transaction_amount_not_equal_to")
     */
    private float $amount;

    /**
     * @ORM\ManyToOne (targetEntity=Pocket::class, inversedBy="financialTransactions")
     * @ORM\JoinColumn (name="pocket_id", referencedColumnName="id", nullable=false)
     */
    private Pocket $pocket;

    /**
     * @ORM\Column (name="transaction_date", type="datetime")
     */
    private DateTime $transactionDate;

    /**
     * @ORM\Column (name="post_transaction_balance", type="decimal", precision=10, scale=2)
     */
    private float $postTransactionBalance;


    /* GETTERS AND SETTERS */

    public function getId () : int
    {
        return $this -> id;
    }

    public function getTitle () : string
    {
        return $this -> title;
    }

    public function setTitle (string $title) : self
    {
        $this -> title = $title;

        return $this;
    }

    public function getAmount () : float
    {
        return $this -> amount;
    }

    public function setAmount (float $amount) : self
    {
        $this -> amount = $amount;

        return $this;
    }

    public function getPocket () : Pocket
    {
        return $this -> pocket;
    }

    public function setPocket (Pocket $pocket) : self
    {
        $this -> pocket = $pocket;

        return $this;
    }

    public function getTransactionDate () : DateTime
    {
        return $this -> transactionDate;
    }

    public function setTransactionDate (DateTime $transactionDate) : self
    {
        $this -> transactionDate = $transactionDate;

        return $this;
    }

    public function getPostTransactionBalance (): float
    {
        return $this -> postTransactionBalance;
    }

    public function setPostTransactionBalance (float $postTransactionBalance) : self
    {
        $this -> postTransactionBalance = $postTransactionBalance;

        return $this;
    }
}