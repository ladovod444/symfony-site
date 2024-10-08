<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Filter\BlogFilter;
use App\Form\BlogFilterType;
use App\Form\BlogType;
use App\Message\CheckUniqueTextJob;
use App\MessageHandler\CheckUniqueTextJobHandler;
use App\Repository\BlogRepository;
use App\Service\CheckUniqueText;
use App\Service\MessageGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Здесь можно задать "главный" урл
#[Route('/admin/blog')]
final class BlogController extends AbstractController
{
    #[Route(name: 'app_blog_index', methods: ['GET'])]
    public function index(Request            $request,
                          BlogRepository     $blogRepository,
                          PaginatorInterface $paginator,
    ): Response
    {

        //dd($blogRepository->findAll());
        $blogFilter = new BlogFilter();
        $form = $this->createForm(BlogFilterType::class, $blogFilter);
        $form->handleRequest($request);
        //dd($blogFilter);

        $pagination = $paginator->paginate(
        //$query, /* query NOT result */
            $blogRepository->findByBlogFilter($blogFilter),
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('blog/index.html.twig', [
            //'blogs' => $blogRepository->findByBlogFilter($blogFilter),
            'pagination' => $pagination,
            'formSearch' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_blog_new', methods: ['GET', 'POST'])]
    public function new(Request                $request,
                        EntityManagerInterface $entityManager,
        /*CheckUniqueText $checkUniqueText*/
                        MessageBusInterface    $bus,
                        MessageGenerator       $messageGenerator): Response
    {
        $blog = new Blog($this->getUser());
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blog);
            $entityManager->flush();

            // TODO проверка уникальности пока здесь далее будет перенесена в очередь,
            // поскольку метод checkUniqueText использует curl и может быть причиной замедления
//      $unique_percent = $checkUniqueText->checkUniqueText($blog->getDescription());
//      $blog->setPercent($unique_percent);

            // ВРЕМЕННО закомментить
            $bus->dispatch(new CheckUniqueTextJob($blog->getId()));

            $entityManager->flush();

            $message = $messageGenerator->getHappyMessage();
            $message .= " Blog post with ID= {$blog->getId()} is created";
            $this->addFlash('success', $message);

            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_show', methods: ['GET'])]
    public function show(Blog $blog): Response
    {
        $blog_tags = [];
        foreach ($blog->getTags() as $tag) {
            $blog_tags[] = $tag->getName();
        }
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
            'blog_tags' => $blog_tags,
        ]);
    }

    #[IsGranted('edit', 'blog')]
    #[Route('/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
    }

  #[Route('/{id}/delete', name: 'app_blog_get_delete', methods: ['GET'])]
  public function deleteBlog(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
  {
    $entityManager->remove($blog);
    $entityManager->flush();
    return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
  }
}
