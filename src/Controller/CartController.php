<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier', name: 'app_cart_')]
class CartController extends AbstractController
{
    #[Route('/ajouter/{id}', name: 'add', methods: ['POST'])]
    public function add(Product $product, Request $request, CartService $cartService): Response
    {
        // Jeton CSRF
        if (!$this->isCsrfTokenValid('cart_add_' . $product->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton de sécurité invalide.');
        }

        // Contrôle de disponibilité
        if ($product->getStock() < 1) {
            $this->addFlash('Attention', 'Cette pièce n\'est plus disponible.');
            return $this->redirectToRoute('app_catalog_show', ['slug' => $product->getSlug()]);
        }

        $quantity = max(1, $request->getPayload()->getInt('quantity', 1));

        $cartService->add($product->getId(), $quantity);

        $this->addFlash('success', sprintf('« %s » a été ajouté à votre panier.', $product->getName()));  // sprintf() sert à construire une chaîne de caractères en remplaçant des marqueurs par des valeurs.

        return $this->redirectToRoute('app_catalog_show', ['slug' => $product->getSlug()]);
    }
}