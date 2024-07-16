<?php

namespace App\Controller;

use App\Repository\PurchaseOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class HomeController extends AbstractController
{
    public function __construct(
        private PurchaseOrderRepository $purchaseOrderRepository,
        private Security $security,
    ) {
    }
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $purchaseOrders = [];

        if ($this->security->getUser()) {
            $purchaseOrders = $this->purchaseOrderRepository->findAll();
        }

        return $this->render('home/index.html.twig', [
            'orders' => $purchaseOrders,
        ]);
    }
}
