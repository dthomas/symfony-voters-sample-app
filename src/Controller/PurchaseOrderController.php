<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PurchaseOrderRepository;

class PurchaseOrderController extends AbstractController
{
    public function __construct(private PurchaseOrderRepository $purchaseOrderRepository)
    {
    }

    #[Route('/approve/{id}', name: 'approve')]
    public function approve(int $id): Response
    {
        $purchaseOrder = $this->purchaseOrderRepository->find($id);

        if (!$purchaseOrder) {
            throw $this->createNotFoundException('Purchase order not found');
        }

        $this->denyAccessUnlessGranted('approve', $purchaseOrder);

        $this->addFlash('success', 'Access Granted');

        return $this->redirect('/');
    }
}
