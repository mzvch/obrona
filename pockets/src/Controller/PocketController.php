<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Entity\Pocket;
use App\Form\DeletePocketType;
use App\Form\PocketType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PocketController extends AbstractController
{
    /**
     * @Route ("/panel-uzytkownika/moje-portfele", name="pocket_list")
     */
    public function list () : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            return $this -> render ("pocket/list.html.twig");
        }
    }

    /**
     * @Route ("/panel-uzytkownika/moje-portfele/dodaj", name="pocket_add")
     */
    public function add (Request $request, TranslatorInterface $translator) : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            $pocket = new Pocket ();

            $form = $this -> createForm (PocketType::class, $pocket);
            $form -> handleRequest ($request);

            if ($form -> isSubmitted () && $form -> isValid ())
            {
                $pocket
                    -> setName ($form -> get ("name") -> getData ())
                    -> setUser ($this -> getUser ())
                ;

                if ($form -> get ("accountBalance") -> getData () === null)
                {
                    $pocket -> setAccountBalance (0.0);
                }
                else
                {
                    $pocket -> setAccountBalance ($form -> get ("accountBalance") -> getData ());
                }

                $entityManager = $this -> getDoctrine () -> getManager ();
                $entityManager -> persist ($pocket);
                $entityManager -> flush ();

                $this -> addFlash ("success", $translator -> trans ("flash_message.add_pocket_success", [], "flash_messages"));

                return $this -> redirectToRoute ('pocket_list');
            }

            return $this -> render ("pocket/add.html.twig",
                [
                    "form" => $form -> createView ()
                ]);
        }
    }

    /**
     * @Route ("/panel-uzytkownika/moje-portfele/edytuj/{id}", name="pocket_edit")
     */
    public function edit (Request $request, Pocket $pocket, TranslatorInterface $translator) : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            $form = $this -> createForm (PocketType::class, $pocket);
            $form -> handleRequest ($request);

            if ($form -> isSubmitted () && $form -> isValid ())
            {
                $pocket -> setName ($form -> get ("name") -> getData ());

                if ($form -> get ("accountBalance") -> getData () === null)
                {
                    $pocket -> setAccountBalance (0.0);
                }
                else
                {
                    $pocket -> setAccountBalance ($form -> get ("accountBalance") -> getData ());
                }

                $entityManager = $this -> getDoctrine () -> getManager ();
                $entityManager -> persist ($pocket);
                $entityManager -> flush ();

                $this -> addFlash ("success", $translator -> trans ("flash_message.edit_pocket_success", [], "flash_messages"));

                return $this -> redirectToRoute ('pocket_list');
            }

            return $this -> render ("pocket/edit.html.twig",
                [
                    "form" => $form -> createView ()
                ]);
        }
    }

    /**
     * @Route ("/panel-uzytkownika/moje-portfele/usun/{id}", name="pocket_delete")
     */
    public function delete (Pocket $pocket, Request $request, TranslatorInterface $translator) : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            $form = $this -> createForm (DeletePocketType::class);
            $form -> handleRequest ($request);

            if ($form -> isSubmitted () && $form -> isValid ())
            {
                $entityManager = $this -> getDoctrine () -> getManager ();

                $entityManager -> remove ($pocket);

                $entityManager -> flush ();

                $this -> addFlash ("success", $translator -> trans ("flash_message.delete_pocket_success", [], "flash_messages"));

                return $this -> redirectToRoute ("pocket_list");
            }

            return $this -> render ("pocket/delete.html.twig",
                [
                    "form" => $form -> createView ()
                ]);
        }
    }
}