<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\BlogComment;
use App\Form\BlogCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogCommentController extends AbstractController
{
  #[Route('/add/comment/{blog}', name: 'blog_add_comment', methods: ['POST'])]
  public function addComment(Blog $blog, Request $request, EntityManagerInterface $entityManager): Response
  {
    $blogComment = new BlogComment();
    $commentForm = $this->createForm(BlogCommentType::class, $blogComment);
    $commentForm->handleRequest($request);

    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
      //dd($commentForm->getData());

      $blogComment->setBlog($blog);
      $blogComment->setText($commentForm->getData()->getText());

      $entityManager->persist($blogComment);
      $entityManager->flush();

      return $this->redirectToRoute('blog_view', ['id'=>$blog->getId()], Response::HTTP_SEE_OTHER);
    }

  }
}