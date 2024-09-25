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

class DefaultController extends AbstractController
{
   #[Route('/', name: 'app_default_home')]
    //#[Route('/default/{slug}', name: 'app_default')]
    public function index(BlogRepository $blogRepository): Response
    {
        $blogs = $blogRepository->getBlogs();
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'blogs' => $blogs,
        ]);
    }


}
