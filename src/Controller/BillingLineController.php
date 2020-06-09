<?php

namespace App\Controller;

use App\Entity\BillingLine;
use App\Form\BillingLineType;
use App\Repository\BillingLineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/billing/line")
 */
class BillingLineController extends AbstractController
{
    /**
     * @Route("/{id}", name="billing_line_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BillingLine $billingLine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$billingLine->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($billingLine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('billing_line_index');
    }
}
