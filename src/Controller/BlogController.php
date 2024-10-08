<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\BlogComment;
use App\Filter\BlogFilter;
use App\Form\BlogCommentType;
use App\Form\BlogFilterType;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Здесь можно задать "главный" урл
#[Route('/blog')]
final class BlogController extends AbstractController
{

  #[Route('/{id}', name: 'blog_view', methods: ['GET', 'POST'])]
  public function show(Blog $blog, Request $request): Response
  {
    $blog_tags = [];
    foreach ($blog->getTags() as $tag) {
      $blog_tags[] = $tag->getName();
    }

    // Для формы создаем action - /add/comment/1625
    // Для этого action создан отдельный контроллер
    // src/Controller/BlogCommentController.php и action addComment
    $commentForm = $this->createForm(
      BlogCommentType::class,
      null,
      ['action' => $this->generateUrl('blog_add_comment', ['blog' => $blog->getId()])]
    );

    //$blogCommments = $blog->getComments();// По сути это не нужно делать
    // Потому что при создании Entity Comment было также создано
    // поле comments у Entity Blog src/Entity/Blog.php - private Collection $comments;
    // а также геттер для этого поля - getComments
    // Это позволяет получать св-во blog.comments в шаблоне twig

    return $this->render('blog/show.html.twig', [
      'blog' => $blog,
      'blog_tags' => $blog_tags,
      //'blog_comments' => $blogCommments,
      'commentForm' => $commentForm->createView(),
    ]);
  }

  public function testAction(Request $request): Response
  {
    $content = $request->getContent();
    //dd($content);

    //dd($request->query->get('test'));
    //dd($request->cookies);
//        dd($request->attributes);
//        dd($request->headers->get('User-Agent'));

    //echo $request->query->get('test', 'default');

    //$session = $this->requestStack->getSession();
    $session = $request->getSession();
    //var_dump($session);

    //https://symfony.com/doc/current/session.html

    return $this->render('blog/test.html.twig');
  }

  //public function __invoke(Request $request, string|int $slug): Response
  public function __invoke(Blog $blog): Response
  {
    dd($blog);
    //echo $slug;
    return $this->render('blog/test.html.twig');
    // TODO: Implement __invoke() method.
  }

  #[Route('/test/exp', name: 'blog_exp', methods: ['GET', 'POST'])]
  public function exp(EntityManagerInterface $entityManager, UserRepository $userRepository): Response
  {
    $user = $this->getUser() ? $this->getUser() : $userRepository->findOneBy(['email' => 'ladovod@gmail.com']);
    $blog = new Blog($user);

    $blog->setTitle('title')
      ->setDescription('description')
      ->setText('text')
    ->setStatus('pending');

    $blog->addComment((new BlogComment())->setText('blog comment text'));

    $entityManager->persist($blog);
    $entityManager->flush();

    return new Response('exp');
  }


}
