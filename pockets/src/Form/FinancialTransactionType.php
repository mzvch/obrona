<?php
declare (strict_types = 1);

namespace App\Form;

use App\Entity\FinancialTransaction;
use App\Entity\Pocket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class FinancialTransactionType extends AbstractType
{
    private Security $security;

    public function __construct (Security $security)
    {
        $this -> security = $security;
    }

    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add ("title", TextType::class,
                [
                    "label" => "form.financial_transaction.label.title",
                    "help" => "form.financial_transaction.help.title",
                    "translation_domain" => "financial_transaction"
                ])

            -> add ("pocket", EntityType::class,
                [
                    "label" => "form.financial_transaction.label.pocket",
                    "class" => Pocket::class,
                    "choices" => $this -> security -> getUser () -> getPockets (),
                    "choice_label" => "name",
                    "translation_domain" => "financial_transaction"
                ])

            -> add ("amount", MoneyType::class,
                [
                    "label" => "form.financial_transaction.label.amount",
                    "help" => "form.financial_transaction.help.amount",
                    "currency" => null,
                    "translation_domain" => "financial_transaction"
                ])

            -> add ("submit", SubmitType::class,
                [
                    "label" => "form.financial_transaction.label.submit",
                    "validate" => false,
                    "translation_domain" => "financial_transaction"
                ])
        ;
    }

    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver -> setDefaults ([
            'data_class' => FinancialTransaction::class
        ]);
    }
}