<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ConferenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
//use Twig\Environment;

class ConferenceController extends AbstractController
{
  #[Route('/conference', name: 'app_conference')]
  //public function index(Environment $twig, ConferenceRepository $conferenceRepository): Response
  public function index(ConferenceRepository $conferenceRepository): Response
  {

    return $this->render('conference/index.html.twig', [
      'conferences' => $conferenceRepository->findAll(),
      'controller_name' => __FUNCTION__,
    ]);
  }

  #[Route('/conferences/{id}', name: 'conference')]
 // public function show(Request $request, Environment $twig, Conference $conference, CommentRepository $commentRepository): Response
  public function show(Request $request, Conference $conference, CommentRepository $commentRepository): Response
  {
    $offset = max(0, $request->query->getInt('offset', 0));
    $paginator = $commentRepository->getCommentPaginator($conference, $offset);

    return $this->render('conference/show.html.twig', [
      'conference' => $conference,

      'comments' => $paginator,
      'previous' => $offset - CommentRepository::COMMENTS_PER_PAGE,
      'next' => min(count($paginator), $offset + CommentRepository::COMMENTS_PER_PAGE),
    ]);
  }
}
