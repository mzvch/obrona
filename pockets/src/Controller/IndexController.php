<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Form\ChangeLanguageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route ("/", name="index")
     */
    public function index () : Response
    {
        return $this -> render ("index/index.html.twig");
    }

    /**
     * @Route ({
     *     "pl": "/zmien-jezyk",
     *     "en": "/change-language"
     * }, name="change_language")
     */
    public function changeLanguage (Request $request) : Response
    {
        $form = $this -> createForm (ChangeLanguageType::class);
        $form -> handleRequest ($request);

        if ($form -> isSubmitted () && $form -> isValid ())
        {
            $request -> getSession () -> set ('_locale', $form -> getData () ['language']);

            return $this -> redirectToRoute ("user_panel");
        }

        return $this -> render ("index/change_language.html.twig",
            [
                "form" => $form -> createView ()
            ]);
    }
}