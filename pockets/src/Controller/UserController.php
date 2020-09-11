<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\EditPersonalDataType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/panel-uzytkownika", name="user_panel")
     */
    public function panel () : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            return $this -> render ("user/panel.html.twig");
        }
    }

    /**
     * @Route ("/panel-uzytkownika/moje-dane", name="user_personal_data")
     */
    public function personalData () : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            return $this -> render ("user/personal_data.html.twig");
        }
    }

    /**
     * @Route ("/panel-uzytkownika/moje-dane/edytuj", name="user_edit_personal_data")
     */
    public function editPersonalData (Request $request, TranslatorInterface $translator) : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            $entityManger = $this -> getDoctrine () -> getManager ();

            $user = $entityManger -> getRepository (User::class) -> findOneBy (["email" => $this -> getUser () -> getUsername ()]);
            $form = $this -> createForm (EditPersonalDataType::class, $user);

            $form -> handleRequest ($request);

            if ($form -> isSubmitted () && $form -> isValid ())
            {
                $entityManger -> persist ($user);
                $entityManger -> flush ();

                $this -> addFlash ("success", $translator -> trans ("flash_message.edit_personal_data_success", [], "flash_messages"));

                return $this -> redirectToRoute ("user_personal_data");
            }

            return $this -> render ("user/edit_personal_data.html.twig",
                [
                    "form" => $form -> createView ()
                ]);
        }
    }

    /**
     * @Route ("/panel-uzytkownika/moje-dane/zmien-haslo", name="user_change_password")
     */
    public function changePassword (Request $request, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface  $translator) : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            $entityManger = $this -> getDoctrine () -> getManager ();

            $user = $entityManger -> getRepository (User::class) -> findOneBy (["email" => $this -> getUser () -> getUsername ()]);

            $form = $this -> createForm (ChangePasswordType::class, $user);
            $form -> handleRequest ($request);

            if ($form -> isSubmitted () && $form -> isValid ())
            {
                $user
                    -> setPassword (
                        $passwordEncoder -> encodePassword (
                            $user,
                            $form -> get ("password") -> getData ()
                        )
                );

                $entityManger -> persist ($user);
                $entityManger -> flush ();

                $this -> addFlash ("success", $translator -> trans ("flash_message.change_password_success", [], "flash_messages"));

                return $this -> redirectToRoute ("user_personal_data");
            }

            return $this -> render ("user/change_password.html.twig",
                [
                    "form" => $form -> createView ()
                ]);
        }
    }

    /**
     * @Route ("/panel-uzytkownika/ustawienia-konta", name="user_account_settings")
     */
    public function accountSettings () : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            return $this -> render ("user/account_settings.html.twig");
        }
    }
}