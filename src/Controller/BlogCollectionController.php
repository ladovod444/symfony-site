<?php

namespace App\Controller;

use App\Entity\BlogCollection;
use App\Form\BlogCollectionType;
use App\Repository\BlogCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blog/collection')]
final class BlogCollectionController extends AbstractController
{
    #[Route(name: 'app_blog_collection_index', methods: ['GET'])]
    public function index(BlogCollectionRepository $blogCollectionRepository): Response
    {
        return $this->render('blog_collection/index.html.twig', [
            'blog_collections' => $blogCollectionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_blog_collection_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blogCollection = new BlogCollection();
        $form = $this->createForm(BlogCollectionType::class, $blogCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blogCollection);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_collection/new.html.twig', [
            'blog_collection' => $blogCollection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_collection_show', methods: ['GET'])]
    public function show(BlogCollection $blogCollection): Response
    {
        return $this->render('blog_collection/show.html.twig', [
            'blog_collection' => $blogCollection,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_collection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogCollection $blogCollection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogCollectionType::class, $blogCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_collection/edit.html.twig', [
            'blog_collection' => $blogCollection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_collection_delete', methods: ['POST'])]
    public function delete(Request $request, BlogCollection $blogCollection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogCollection->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($blogCollection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_collection_index', [], Response::HTTP_SEE_OTHER);
    }
}
