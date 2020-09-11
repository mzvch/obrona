<?php
declare (strict_types = 1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPersonalDataType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options) : void
    {
        $builder
            -> add ("firstName", TextType::class,
                [
                    "label" => "form.edit_personal_data.label.first_name",
                    "help" => "form.edit_personal_data.help.first_name",
                    "translation_domain" => "edit_personal_data"
                ])

            -> add ("lastName", TextType::class,
                [
                    "label" => "form.edit_personal_data.label.last_name",
                    "help" => "form.edit_personal_data.help.last_name",
                    "translation_domain" => "edit_personal_data"
                ])

            -> add ("submit", SubmitType::class,
                [
                    "label" => "form.edit_personal_data.label.submit",
                    "validate" => false,
                    "translation_domain" => "edit_personal_data"
                ])
        ;
    }

    public function configureOptions (OptionsResolver $resolver) : void
    {
        $resolver -> setDefaults ([
            "data_class" => User::class
        ]);
    }
}