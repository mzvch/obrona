<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\DeleteAccountType;
use App\Form\EditPersonalDataType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends AbstractController
{
    /**
     * @Route ("/admin/lista-uzytkownikow", name="admin_user_list")
     */
    public function userList () : Response
    {
        if (!$this -> getUser () || !in_array (User::ROLE_ADMIN, $this -> getUser () -> getRoles ()))
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            $entityManager = $this -> getDoctrine () -> getManager ();

            $users = $entityManager -> getRepository (User::class) -> findAll ();

            unset ($users [array_search ($this -> getUser (), $users)]);

            return $this -> render ("admin/user_list.html.twig",
                [
                    "users" => $users
                ]);
        }
    }

    /**
     * @Route ("/admin/lista-uzytkownikow/dane-uzytkownika/{id}", name="admin_user_data")
     */
    public function userData (User $user) : Response
    {
        if (!$this -> getUser () || !in_array (User::ROLE_ADMIN, $this -> getUser () -> getRoles ()))
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            return $this -> render ("admin/user_data.html.twig",
                [
                    "user" => $user
                ]);
        }
    }

    /**
     * @Route ("/admin/lista-uzytkownikow/dane-uzytkownika/{id}/edytuj", name="admin_edit_user_personal_data")
     */
    public function editUserPersonalData (User $user, Request $request, TranslatorInterface $translator) : Response
    {
        if (!$this -> getUser () || !in_array (User::ROLE_ADMIN, $this -> getUser () -> getRoles ()))
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            $entityManger = $this -> getDoctrine () -> getManager ();

            $user = $entityManger -> getRepository (User::class) -> findOneBy (["email" => $user -> getUsername ()]);
            $form = $this -> createForm (EditPersonalDataType::class, $user);

            $form -> handleRequest ($request);

            if ($form -> isSubmitted () && $form -> isValid ())
            {
                $entityManger -> persist ($user);
                $entityManger -> flush ();

                $this -> addFlash ("success", $translator -> trans ("flash_message.edit_user_personal_data_success", [], "flash_messages"));

                return $this -> redirectToRoute ("admin_user_data", ["id" => $user -> getId ()]);
            }

            return $this -> render ("admin/edit_user_personal_data.html.twig",
                [
                    "form" => $form -> createView (),
                    "user" => $user
                ]);
        }
    }

    /**
     * @Route ("/admin/lista-uzytkownikow/dane-uzytkownika/{id}/zmien-haslo", name="admin_change_user_password")
     */
    public function changeUserPassword (User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator) : Response
    {
        if (!$this -> getUser () || !in_array (User::ROLE_ADMIN, $this -> getUser () -> getRoles ()))
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            $entityManger = $this -> getDoctrine () -> getManager ();

            $user = $entityManger -> getRepository (User::class) -> findOneBy (["email" => $user -> getUsername ()]);

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

                $this -> addFlash ("success", $translator -> trans ("flash_message.edit_change_user_password_success", [], "flash_messages"));

                return $this -> redirectToRoute ("admin_user_data", ["id" => $user -> getId ()]);
            }

            return $this -> render ("admin/change_user_password.html.twig",
                [
                    "form" => $form -> createView (),
                    "user" => $user
                ]);
        }
    }

    /**
     * @Route ("/admin/lista-uzytkownikow/usun-uzytkownika/{id}", name="admin_delete_user")
     */
    public function deleteUser (User $user, Request $request, TranslatorInterface $translator) : Response
    {
        if (!$this -> getUser () || !in_array (User::ROLE_ADMIN, $this -> getUser () -> getRoles ()))
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            $form = $this -> createForm (DeleteAccountType::class);
            $form -> handleRequest ($request);

            if ($form -> isSubmitted () && $form -> isValid ())
            {
                $entityManager = $this -> getDoctrine () -> getManager ();

                $entityManager -> remove ($user);

                $entityManager -> flush ();

                $this -> addFlash ("success", $translator -> trans ("flash_message.delete_user_success", [], "flash_messages"));

                return $this -> redirectToRoute ("admin_user_list");
            }

            return $this -> render ("admin/delete_user.html.twig",
                [
                    "form" => $form -> createView ()
                ]);
        }
    }
}