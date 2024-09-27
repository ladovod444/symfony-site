<?php

namespace App\Controller;

use App\Entity\Page;
use App\Filter\PageFilter;
use App\Form\PageFilterType;
use App\Form\PageType;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user/page')]
final class PageController extends AbstractController
{
  #[Route(name: 'app_user_page_index', methods: ['GET'])]
  public function index(Request $request, PageRepository $pageRepository, PaginatorInterface $paginator): Response
  {

    $pageFilter = new PageFilter($this->getUser());
    $form = $this->createForm(PageFilterType::class, $pageFilter);
    $form->handleRequest($request);

    $pagination = $paginator->paginate(
    //$query, /* query NOT result */
      $pageRepository->findByPageFilter($pageFilter),
      $request->query->getInt('page', 1), /*page number*/
      5 /*limit per page*/
    );

//      if ($form->isSubmitted() && $form->isValid()) {
//      }

    return $this->render('page/index.html.twig', [
      //'pages' => $pageRepository->findByPageFilter($pageFilter),
      'pagination' => $pagination,
      'formSearch' => $form->createView(),
    ]);
//        return $this->render('page/index.html.twig', [
//            'pages' => $pageRepository->findAll(),
//        ]);
  }

  #[Route('/new', name: 'app_user_page_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $page = new Page($this->getUser());
    $form = $this->createForm(PageType::class, $page);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($page);
      $entityManager->flush();

      return $this->redirectToRoute('app_user_page_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('page/new.html.twig', [
      'page' => $page,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_user_page_show', methods: ['GET'])]
  public function show(Page $page): Response
  {
    //dd($page);
    $page_tags = [];
    foreach ($page->getTags() as $tag) {
      $page_tags[] = $tag->getName();
    }

    return $this->render('page/show.html.twig', [
      'page' => $page,
      'page_tags' => $page_tags,
    ]);
  }

  #[IsGranted('edit', 'page')]
  #[Route('/{id}/edit', name: 'app_user_page_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Page $page, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(PageType::class, $page);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_user_page_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('page/edit.html.twig', [
      'page' => $page,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_user_page_delete', methods: ['POST'])]
  public function delete(Request $request, Page $page, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $page->getId(), $request->getPayload()->getString('_token'))) {
      $entityManager->remove($page);
      $entityManager->flush();
    }

    return $this->redirectToRoute('app_user_page_index', [], Response::HTTP_SEE_OTHER);
  }
}
