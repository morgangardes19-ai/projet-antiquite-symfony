<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Gère le panier du visiteur, stocké en session.
 * Format : [idProduit => quantité]
 */
class CartService
{
    private const SESSION_KEY = 'cart'; // SESSION_KEY est le nom que j'ai choisi pour la constante (privée = accessible uniquement à l'intérieur de la classe)

    public function __construct(
        private RequestStack $requestStack,  // RequestStack est injecté, il donne accès à la requête en cours, donc à la session. Sans avoir à passer en paramètre chaque méthode.
    ) {}

    private function getSession(): SessionInterface  // Une méthode privée qui centralise l'accès à la session. Toutes les autres méthodes l'utilisent.
    {
        return $this->requestStack->getSession();
    }

    /** 
     * Récupère les produits du panier et leur quantité
     * @return array<int, int> */
    public function getItems(): array
    {
        return $this->getSession()->get(self::SESSION_KEY, []);
    }

    /**
     * Méthode qui permet d'obtenir la quantité d'un produit dans le panier à partir de son id
     */
    public function getQuantity(int $productId): int
    {
        // Si j'ai le produit dans le panier je return sa quantité sinon je return 0
        return $this->getItems()[$productId] ?? 0;
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $items = $this->getItems();
        $items[$productId] = ($items[$productId] ?? 0) + $quantity; // Pour ce produit, récupère la quantité déjà présente dans le panier, ou 0 s'il n'y en a pas, puis ajoute la nouvelle quantité
        $this->getSession()->set(self::SESSION_KEY, $items);
    }

    public function update(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($productId);
            return;
        }
        $items = $this->getItems();
        $items[$productId] = $quantity;
        $this->getSession()->set(self::SESSION_KEY, $items);
    }

    public function remove(int $productId): void
    {
        $items = $this->getItems();
        unset($items[$productId]);
        $this->getSession()->set(self::SESSION_KEY, $items);
    }

    public function clear(): void
    {
        $this->getSession()->remove(self::SESSION_KEY);
    }

    public function getTotalQuantity(): int
    {
        return array_sum($this->getItems());
    }

    public function isEmpty(): bool
    {
        return [] === $this->getItems();
    }
}
