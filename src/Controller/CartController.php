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
        // ① Combien en reste-t-il VRAIMENT ?
        $alreadyInCart = $cartService->getQuantity($product->getId());
        $remaining     = max(0, $product->getStock() - $alreadyInCart);

        // ② Le panier est déjà plein → on refuse
        if ($remaining < 1) {
            $this->addFlash('danger', sprintf(
                'Vous avez déjà les %d exemplaire(s) disponible(s) de cette pièce dans votre panier.',
                $product->getStock()
            ));

            return $this->redirectToRoute('app_catalog_show', [
                'slug' => $product->getSlug(),
            ]);
        }

        $form = $this->createForm(CartItemType::class, null, [
            'max_stock' => $product->getStock(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quantity = $form->get('quantity')->getData();

            // Si j'en veux plus que ce qu'il reste, mon vouloir devient la quantité restante
            if ($remaining - $quantity < 0) {
                $quantity = $remaining;
            }

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

    #[Route('/modifier/{id}', name: 'update', methods: ['POST'])]
    public function update(Product $product, Request $request, CartService $cartService): Response
    {
        if (!$this->isCsrfTokenValid(
            'cart_update_' . $product->getId(),
            $request->getPayload()->getString('_token')
        )) {
            throw $this->createAccessDeniedException('Jeton de sécurité invalide.');
        }

        $quantity = $request->getPayload()->getInt('quantity', 1);

        if ($quantity > $product->getStock()) {
            $quantity = $product->getStock(); // si un utilisateur tape 999 alors qu'il reste 3, la quantité est ramenée à 3
            $this->addFlash('attention', sprintf(
                'Il ne reste que %d exemplaire(s) de cette pièce.',
                $product->getStock()
            ));
        } else {
            $this->addFlash('success', 'Panier mis à jour.');
        }

        $cartService->update($product->getId(), $quantity);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/supprimer/{id}', name: 'remove', methods: ['POST'])]
    public function remove(Product $product, Request $request, CartService $cartService): Response
    {
        if (!$this->isCsrfTokenValid(
            'cart_remove_' . $product->getId(),
            $request->getPayload()->getString('_token')
        )) {
            throw $this->createAccessDeniedException('Jeton de sécurité invalide.');
        }

        $cartService->remove($product->getId());

        $this->addFlash('success', sprintf(
            '« %s » a été retiré de votre panier.',
            $product->getName()
        ));

        return $this->redirectToRoute('app_cart_index');
    }
}
