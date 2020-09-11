<?php
declare (strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteAccountType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add ("submit", SubmitType::class,
                [
                    "label" => "form.delete_account.label.submit",
                    "validate" => false,
                    "translation_domain" => "delete_account"
                ])
        ;
    }

    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver -> setDefaults ([]);
    }
}