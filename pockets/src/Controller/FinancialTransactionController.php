<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Entity\FinancialTransaction;
use App\Form\FinancialTransactionType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class FinancialTransactionController extends AbstractController
{
    /**
     * @Route ("/panel-uzytkownika/moje-operacje/lista/{page}", name="financial_transaction_list")
     */
    public function list (Request $request, int $page = 1) : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            /*$pockets = $this -> getUser () -> getPockets ();

            $allUserFinancialTransactions = [];

            foreach ($pockets as $pocket)
            {
                $financialTransactions = $pocket -> getFinancialTransactions ();

                foreach ($financialTransactions as $financialTransaction)
                {
                    $allUserFinancialTransactions [] = $financialTransaction;
                }
            }

            return $this -> render ("financial_transaction/list.html.twig",
                [
                    "financialTransactions" => $allUserFinancialTransactions
                ]);*/

            if ($request -> isMethod ('POST'))
            {
                $filters = $request -> request -> all ();

                $request -> getSession () -> set ('filters', $filters);
            }
            else
            {
                $filters = $request -> getSession () -> get ('filters', []);
            }

            $repository = $this -> getDoctrine () -> getRepository (FinancialTransaction::class);

            $allUserFinancialTransactions = $repository -> findAllAssignedToUser ($this -> getUser () -> getId (), $page, $filters);

            $count = (int) $repository -> countTransactions ($this -> getUser () -> getId (), $filters);

            return $this -> render ("financial_transaction/list.html.twig",
                [
                    "financialTransactions" => $allUserFinancialTransactions,
                    "filters" => $filters,
                    "pages" => $count <= 3 ? 1 : round($count / 3),
                    "currentPage" => $page
                ]);
        }
    }

    /**
     * @Route ("/panel-uzytkownika/moje-operacje/dodaj", name="financial_transaction_add")
     */
    public function add (Request $request, TranslatorInterface $translator) : Response
    {
        if (!$this -> getUser ())
        {
            return $this -> redirectToRoute ("security_login");
        }
        else
        {
            $userPockets = $this -> getUser () -> getPockets ();

            if (count ($userPockets) == 0)
            {
                $this -> addFlash ("warning", $translator -> trans ("flash_message.no_pockets_warning", [], "flash_messages"));

                return $this -> redirectToRoute ("pocket_add");
            }
            else
            {
                $financialTransaction = new FinancialTransaction ();

                $form = $this -> createForm (FinancialTransactionType::class, $financialTransaction);
                $form -> handleRequest ($request);

                if ($form -> isSubmitted () && $form -> isValid ())
                {
                    $pocket = $form -> get ("pocket") -> getData ();

                    if ($financialTransaction -> getAmount () < 0 && $pocket -> getAccountBalance () < abs ($financialTransaction -> getAmount ()))
                    {
                        $this -> addFlash ("error", $translator -> trans ("flash_message.not_enough_money_error", [], "flash_messages"));

                        return $this -> render ("financial_transaction/add.html.twig",
                            [
                                "form" => $form -> createView ()
                            ]);
                    }

                    $pocket -> setAccountBalance ($pocket -> getAccountBalance () + $financialTransaction -> getAmount ());

                    $financialTransaction
                        -> setTransactionDate (new DateTime ())
                        -> setPostTransactionBalance ($pocket -> getAccountBalance ())
                    ;

                    $entityManager = $this -> getDoctrine () -> getManager ();

                    $entityManager -> persist ($pocket);
                    $entityManager -> persist ($financialTransaction);

                    $entityManager -> flush ();

                    $this -> addFlash ("success", $translator -> trans ("flash_message.add_transaction_success", [], "flash_messages"));

                    return $this -> redirectToRoute ("financial_transaction_list");
                }

                return $this -> render ("financial_transaction/add.html.twig",
                    [
                        "form" => $form -> createView ()
                    ]);
            }
        }
    }
}