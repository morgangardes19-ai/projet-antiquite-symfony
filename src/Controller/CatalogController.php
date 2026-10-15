<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
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

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(string $slug, ProductRepository $productRepository): Response
    {
        
        return $this->render('catalog/show.html.twig', [
            'product' => $productRepository->findBy(["slug" => $slug]),
        ]);
    }
}
