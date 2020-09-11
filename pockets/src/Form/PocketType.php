<?php
declare (strict_types = 1);

namespace App\Form;

use App\Entity\Pocket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PocketType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options) : void
    {
        $builder
            -> add ("name", TextType::class,
                [
                    "label" => "form.pocket.label.name",
                    "help" => "form.pocket.help.name",
                    "translation_domain" => "pocket"
                ])

            -> add ("accountBalance", MoneyType::class,
                [
                    "label" => "form.pocket.label.account_balance",
                    "help" => "form.pocket.help.account_balance",
                    "currency" => null,
                    "translation_domain" => "pocket"
                ])

            -> add ("submit", SubmitType::class,
                [
                    "label" => "form.pocket.label.submit",
                    "validate" => false,
                    "translation_domain" => "pocket"
                ])
        ;
    }

    public function configureOptions (OptionsResolver $resolver) : void
    {
        $resolver -> setDefaults ([
            "data_class" => Pocket::class
        ]);
    }
}