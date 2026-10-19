<?php

namespace App\Controller;

use App\Form\CartItemType;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/catalogue', name: 'app_catalog_')]
class CatalogController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $page  = max(1, $request->query->getInt('page', 1));
        $limit = 12;

        $products = $productRepository->findPaginated($page, $limit);

        return $this->render('catalog/index.html.twig', [
            'products'   => $products,
            'page'       => $products->getPageNumber(),
            'totalPages' => $products->getPageCount(),
            'total'      => $products->getTotalCount(),
        ]);
    }

    // // route temporaire pour voir le panier
    // #[Route('/panier', name: 'show_panier', methods: ['GET'])]
    // public function showPanier(CartService $cartService): Response
    // {

    //     dd($cartService->getItems());
    //     return $this->render('cart/temporaire_show.html.twig', [

    //     ]);
    // }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(string $slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw $this->createNotFoundException('Cette pièce n\'existe pas.');
        }

        $cartForm = $this->createForm(CartItemType::class, null, [
            'max_stock' => $product->getStock(),
            'action'    => $this->generateUrl('app_cart_add', ['id' => $product->getId()]),
        ]);

        return $this->render('catalog/show.html.twig', [
            'product'  => $product,
            'cartForm' => $cartForm,
        ]);
    }
}
