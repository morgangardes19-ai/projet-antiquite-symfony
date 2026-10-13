<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/produits')]
#[IsGranted('ROLE_ADMIN')]
final class ProductController extends AbstractController
{
    #[Route('', name: 'admin_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'admin_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($slugger->slug($product->getName())->toString());
            $product->setCreatedAt(new \DateTimeImmutable());

            $this->handleImages($form, $product, $entityManager);

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'La pièce a bien été ajoutée au catalogue.');

            return $this->redirectToRoute('admin_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/modifier', name: 'admin_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($slugger->slug($product->getName())->toString());

            $this->handleImages($form, $product, $entityManager);

            $entityManager->flush();

            return $this->redirectToRoute('admin_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_product_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Traite et enregistre les images téléversées de manière sécurisée.
     */
    private function handleImages(FormInterface $form, Product $product, EntityManagerInterface $entityManager): void
    {
        /** @var UploadedFile[]|null $imageFiles */
        $imageFiles = $form->get('imageFiles')->getData();

        if (!$imageFiles) {
            return;
        }

        foreach ($imageFiles as $imageFile) {
            // PROTECTION 2 : l'extension est déduite du CONTENU réel du fichier.
            //    Un fichier nommé "photo.jpg" mais contenant du PHP ne passera pas.
            $extension = $imageFile->guessExtension() ?? 'bin';

            // PROTECTION 3 : nom aléatoire de 32 caractères hexadécimaux.
            //    → impossible d'écraser une image existante
            //    → impossible d'utiliser "../../" pour sortir du dossier
            $newFilename = bin2hex(random_bytes(16)) . '.' . $extension;

            try {
                // PROTECTION 4 : destination dans public/uploads/, jamais dans src/
                $imageFile->move(
                    $this->getParameter('products_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors du téléversement d\'une image.');
                continue;
            }

            $image = new ProductImage();
            $image->setFilename($newFilename);
            $image->setProduct($product);           // côté propriétaire → écrit la clé étrangère
            $product->addProductImage($image);      // on garde la collection en mémoire cohérente

            $entityManager->persist($image);
        }
    }
}
