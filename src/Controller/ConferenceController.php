<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Repository\ConferenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

//use Twig\Environment;

class ConferenceController extends AbstractController
{

  public function __construct(
    private EntityManagerInterface $entityManager,
  )
  {
  }

  #[Route('/conference', name: 'app_conference')]
  //public function index(Environment $twig, ConferenceRepository $conferenceRepository): Response
  public function index(ConferenceRepository $conferenceRepository): Response
  {

    return $this->render('conference/index.html.twig', [
      'conferences' => $conferenceRepository->findAll(),
      'controller_name' => __FUNCTION__,
    ]);
  }

  #[Route('/conferences/{slug}', name: 'conference')]
  // public function show(Request $request, Environment $twig, Conference $conference, CommentRepository $commentRepository): Response
    //public function show(Request $request, Conference $conference, CommentRepository $commentRepository): Response
  public function show(
    Request                           $request,
    Conference                        $conference,
    CommentRepository                 $commentRepository,
    #[Autowire('%photo_dir%')] string $photoDir,
  ): Response
  {

    $offset = max(0, $request->query->getInt('offset', 0));
    $paginator = $commentRepository->getCommentPaginator($conference, $offset);

    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $comment->setConference($conference);

      if ($photo = $form['photoFilename']->getData()) {
        $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
        $photo->move($photoDir, $filename);
        $comment->setPhotoFilename($filename);
      }

      $this->entityManager->persist($comment);
      $this->entityManager->flush();

      return $this->redirectToRoute('conference', ['slug' => $conference->getSlug()]);
    }

    return $this->render('conference/show.html.twig', [
      'conference' => $conference,

      'comments' => $paginator,
      'previous' => $offset - CommentRepository::COMMENTS_PER_PAGE,
      'next' => min(count($paginator), $offset + CommentRepository::COMMENTS_PER_PAGE),
      'comment_form' => $form,
    ]);
  }
}
