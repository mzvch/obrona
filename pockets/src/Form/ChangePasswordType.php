<?php
declare (strict_types = 1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add ("password", RepeatedType::class,
                [
                    "mapped" => false,
                    "type" => PasswordType::class,
                    "invalid_message" => "change_password_not_same",
                    "translation_domain" => "change_password",
                    "first_options" => [
                        "label" => "form.change_password.label.password",
                        "help" => "form.change_password.help.password"
                    ],
                    "second_options" => [
                        "label" => "form.change_password.label.repeat_password"
                    ],
                    "constraints" => [
                        new NotBlank (
                            [
                                "message" => "change_password_not_blank"
                            ]
                        ),
                        new Length (
                            [
                                "min" => 15,
                                "minMessage" => "change_password_min_length"
                            ]
                        ),
                        new Length (
                            [
                                "max" => 64,
                                "maxMessage" => "change_password_max_length"
                            ]
                        )
                    ]
                ])

            -> add ("submit", SubmitType::class,
                [
                    "label" => "form.change_password.label.submit",
                    "validate" => false,
                    "translation_domain" => "change_password"
                ])
        ;
    }

    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver -> setDefaults ([
            "data_class" => User::class
        ]);
    }
}