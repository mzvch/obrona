<?php
declare (strict_types = 1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add ("firstName", TextType::class,
                [
                    "label" => "form.register.label.first_name",
                    "help" => "form.register.help.first_name",
                    "translation_domain" => "registration_form",
                    "attr" => [
                        "autofocus" => true
                    ]
                ])
            -> add ("lastName", TextType::class,
                [
                    "label" => "form.register.label.last_name",
                    "help" => "form.register.help.last_name",
                    "translation_domain" => "registration_form"
                ])
            -> add ("email", EmailType::class,
                [
                    "label" => "form.register.label.email",
                    "help" => "form.register.help.email",
                    "translation_domain" => "registration_form"
                ])

            -> add ("password", RepeatedType::class,
                [
                    "mapped" => false,
                    "type" => PasswordType::class,
                    "invalid_message" => "user_password_not_same",
                    "translation_domain" => "registration_form",
                    "first_options" => [
                        "label" => "form.register.label.password",
                        "help" => "form.register.help.password",
                    ],
                    "second_options" => [
                        "label" => "form.register.label.repeat_password",
                    ],
                    "constraints" => [
                        new NotBlank (
                            [
                                "message" => "user_password_not_blank"
                            ]
                        ),
                        new Length (
                            [
                                "min" => 15,
                                "minMessage" => "user_password_min_length"
                            ]
                        ),
                        new Length (
                            [
                                "max" => 64,
                                "maxMessage" => "user_password_max_length"
                            ]
                        )
                    ]
                ])

            -> add ("agreeTerms", CheckboxType::class,
                [
                    "mapped" => false,
                    "label_html" => true,
                    "label" => "form.register.label.agree_terms",
                    "constraints" => [
                        new IsTrue (
                            [
                                "message" => "user_agree_terms_is_true"
                            ]
                        )
                    ],
                    "translation_domain" => "registration_form"
                ])

            -> add ("submit", SubmitType::class,
                [
                    "label" => "form.register.label.submit",
                    "validate" => false,
                    "translation_domain" => "registration_form"
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