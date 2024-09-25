<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultControllerOld extends AbstractController
{
   #[Route('/default/{slug}', name: 'app_default', requirements: ['slug' => '\D+'], defaults: ['slug' => 'best slug'])]
    //#[Route('/default/{slug}', name: 'app_default')]
    public function index(Request $r, $slug): Response
    {
      //$name = $r->get("name");
      $name = $r->query->get("name");

      //dump(1); exit();
      //dd(1);
      //return $this->render('index.html.twig');
        return $this->render('index.html.twig', [
            'controller_name' => 'DefaultController',
            'slug' => $slug,
            'name' => $name,
        ]);
    }

  #[Route('/create-blog', name: 'create_blog')]
  public function createBlog(EntityManagerInterface $em): Response
  {
    $blog = new Blog();
    $blog->setTitle("new blog")
      ->setDescription("new blog descr")
      ->setText("new blog text")
      ->setStatus(false);

    $em->persist($blog);
    $em->flush(); // зафиксировать изменения
    return new Response('test');
  }

  #[Route('/update-blog/{id}', name: 'update_blog')]
  //public function updateBlog($id, BlogRepository $blogRepository, EntityManagerInterface $em): Response
  public function updateBlog(Blog $blog, BlogRepository $blogRepository, EntityManagerInterface $em): Response
  {
    //dd($blog);
    //exit();
    //echo $id; die();
    //$blog = $blogRepository->find($id);
    $blog = $blogRepository->findOneBy(['id' => $id]);

    $blog->setTitle('Updated title')
      ->setDescription('Upd descr-on');

   // $em->persist($blog); - ЭТО метод не нужен если у нас есть уже объект, взятый из БД
    $em->flush(); // зафиксировать изменения

//    var_dump($blog);
//    $blog->setTitle("new blog")
//      ->setDescription("new blog descr")
//      ->setText("new blog text");
//
//    $em->persist($blog);
//    $em->flush(); // зафиксировать изменения
    return new Response($blog->getTitle());
  }

  #[Route('/delete-blog/{id}', name: 'delete_blog')]
  public function deleteBlog($id, BlogRepository $blogRepository, EntityManagerInterface $em): Response
  {
    //echo $id; die();
    //$blog = $blogRepository->find($id);
    $blog = $blogRepository->findOneBy(['id' => $id]);

    $em->remove($blog);
    // $em->persist($blog); - ЭТО метод не нужен если у нас есть уже объект, взятый из БД
    $em->flush(); // зафиксировать изменения

    return new Response('Removed ' . $blog->getTitle());
  }

  #[Route('/refresh-blog/{id}', name: 'refresh_blog')]
  public function refreshBlog($id, BlogRepository $blogRepository, EntityManagerInterface $em): Response
  {
    $blog = $blogRepository->findOneBy(['id' => $id]);
    $em->refresh($blog);
    $em->flush(); // зафиксировать изменения
    return new Response('Refreshed ' . $blog->getTitle());
  }
}
