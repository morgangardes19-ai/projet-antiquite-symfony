<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CartItemType;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier', name: 'app_cart_')]
class CartController extends AbstractController
{

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(CartService $cartService, ProductRepository $productRepository): Response
    {
        $items = $cartService->getItems();

        $lines = [];
        $total = 0.0;

        if ($items !== []) {
            $products = $productRepository->findBy(['id' => array_keys($items)]);

            foreach ($products as $product) {
                $quantity = $items[$product->getId()];
                $subtotal = (float) $product->getPrice() * $quantity;
                $total   += $subtotal;

                $lines[] = [
                    'product'  => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }
        }

        return $this->render('cart/index.html.twig', [
            'lines' => $lines,
            'total' => $total,
        ]);
    }

    #[Route('/ajouter/{id}', name: 'add', methods: ['POST'])]
    public function add(Product $product, Request $request, CartService $cartService): Response
    {
        $form = $this->createForm(CartItemType::class, null, [
            'max_stock' => $product->getStock(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quantity = $form->get('quantity')->getData();

            $cartService->add($product->getId(), $quantity);

            $this->addFlash(
                'success',
                sprintf('« %s » a été ajouté à votre panier.', $product->getName())
            );
        } else {
            $this->addFlash('danger', 'La quantité demandée n\'est pas valide.');
        }

        return $this->redirectToRoute('app_catalog_show', [
            'slug' => $product->getSlug(),
        ]);
    }
}
