<?php
declare (strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeLanguageType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add ("language", ChoiceType::class,
                [
                    "label" => "form.change_language.label.language",
                    "translation_domain" => "change_language",
                    "choices" => [
                        "form.change_language.choice.label.polish" => 'pl',
                        "form.change_language.choice.label.english" => 'en'
                    ]
                ])

            -> add ("submit", SubmitType::class,
                [
                    "label" => "form.change_language.label.submit",
                    "validate" => false,
                    "translation_domain" => "change_language"
                ])
        ;
    }

    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver -> setDefaults ([]);
    }
}