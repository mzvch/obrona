<?php
declare (strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeletePocketType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add ("submit", SubmitType::class,
                [
                    "label" => "form.pocket.delete.label.submit",
                    "validate" => false,
                    "translation_domain" => "pocket"
                ])
        ;
    }

    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver -> setDefaults ([]);
    }
}