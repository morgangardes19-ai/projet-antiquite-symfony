<?php

namespace App\Controller\Admin;

use App\Repository\OrdersRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'admin_')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'dashboard', methods: ['GET'])]
    public function index(
        ProductRepository $productRepository,
        OrdersRepository $ordersRepository,
        UserRepository $userRepository,
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'totalProducts'  => $productRepository->count([]),
            'totalOrders'    => $ordersRepository->count([]),
            'totalCustomers' => $userRepository->count([]),
            'catalogValue'   => $productRepository->getCatalogValue(),
            'lowStock'       => $productRepository->findLowStock(3),
        ]);
    }
}