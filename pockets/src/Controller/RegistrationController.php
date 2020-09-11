<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route ({
     *     "pl": "/rejestracja",
     *     "en": "/registration"
     * }, name="register")
     */
    public function register (Request $request, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator) : Response
    {
        if ($this -> getUser ())
        {
            return $this -> redirectToRoute ("index");
        }
        else
        {
            $user = new User ();
            $form = $this -> createForm (RegistrationFormType::class, $user);
            $form -> handleRequest ($request);

            if ($form -> isSubmitted () && $form -> isValid ())
            {
                $user
                    -> setPassword (
                        $passwordEncoder -> encodePassword (
                            $user,
                            $form -> get ('password') -> getData ()
                        )
                    )
                    -> setRoles (["ROLE_USER"])
                ;

                $entityManager = $this -> getDoctrine () -> getManager ();
                $entityManager -> persist ($user);
                $entityManager -> flush ();

                $this -> addFlash ("success", $translator -> trans ('flash_message.registration_success', [], 'flash_messages'));

                return $this -> redirectToRoute ('security_login');
            }

            return $this -> render ('registration/register.html.twig',
                [
                    'registrationForm' => $form -> createView ()
                ]);
        }
    }
}