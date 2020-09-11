<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Form\DeleteAccountType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route ({
     *     "pl": "/logowanie",
     *     "en": "/login"
     * }, name="security_login")
     */
    public function login (AuthenticationUtils $authenticationUtils) : Response
    {
        if ($this -> getUser ())
        {
             return $this -> redirectToRoute("index");
        }
        else
        {
            $error = $authenticationUtils -> getLastAuthenticationError ();
            $lastUsername = $authenticationUtils->getLastUsername ();

            return $this -> render ('security/login.html.twig',
                [
                    'last_username' => $lastUsername,
                    'error' => $error
                ]);
        }
    }

    /**
     * @Route ({
     *     "pl": "/wyloguj",
     *     "en": "/logout"
     * }, name="security_logout")
     */
    public function logout () {}

    /**
     * @Route ("/panel-uzytkownika/ustawienia-konta/usun-konto", name="security_delete_account")
     */
    public function deleteAccount (Request $request) : Response
    {
        if (!$this -> getUser () || in_array (User::ROLE_ADMIN, $this -> getUser () -> getRoles ()))
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

                $session = $this -> get ("session");
                $session = new Session ();
                $session -> invalidate ();

                $entityManager -> remove ($this -> getUser ());

                $entityManager -> flush ();

                return $this -> redirectToRoute ("security_login");
            }

            return $this -> render ("security/delete_account.html.twig",
                [
                    "form" => $form -> createView ()
                ]);
        }
    }
}