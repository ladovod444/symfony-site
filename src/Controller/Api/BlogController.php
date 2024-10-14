<?php

namespace App\Controller\Api;

use App\Dto\BlogDto;
use App\Entity\Blog;
use App\Entity\User;
use App\Filter\BlogFilter;
use App\Form\BlogType;
use App\Repository\BlogRepository;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Blog posts")]
class BlogController extends AbstractController
{

  public function __construct(private readonly BlogRepository         $blogRepository,
                              private readonly UserRepository         $userRepository,
                              private readonly EntityManagerInterface $entityManager)
  {

  }

  #[Route('/api/blog', name: 'api-blog', methods: ['GET'], format: 'json')]
  #[OA\Response(
    response: 200,
    description: 'Returns Blog posts, yes!',
    content: new OA\JsonContent(
      type: 'array',
      items: new OA\Items(ref: new Model(type: Blog::class, groups: ['full']))
    )
  )]
  public function index(): Response
  {
    $blogs = $this->blogRepository->getBlogs();

    return $this->json(
      $blogs,
      context: [
        //AbstractNormalizer::IGNORED_ATTRIBUTES => ['id', 'title']
        // Делим нормализацию по группам
        AbstractNormalizer::GROUPS => ['only_api_blog'] // будут выводиться только поля помеченные этой группой
      ]);

//    return new JsonResponse([
//      'test' => [
//        'some data' => 'some data',
//      ]
//    ]);
  }

  #[Route('/api/blog', name: 'api-blog-add', methods: ['post'], format: 'json')]
  public function add(Request $request): Response
  {
    //$content = json_decode($request->getContent());
    //dd($content);

    // ! Возможный вариант создания блога через форму

    //$user = $this->userRepository->findOneBy(['email' => 'ladovod@gmail.com']);
    //$user = $this->getUser();
    //dd($this->getUser());

    $blog = new Blog($this->getUser());
    $form = $this->createForm(BlogType::class, $blog);
    $form->submit($request->toArray());

    if ($form->isSubmitted() && $form->isValid()) {

      $this->entityManager->persist($blog);
      $this->entityManager->flush();

      return $this->json($blog, Response::HTTP_CREATED);
    } else {
      return $this->json((string)$form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }

  }

  #[Route('/api/blog/{blog}', name: 'api-blog-update', methods: ['put'], format: 'json')]
  public function update(Blog $blog, Request $request): Response
  {
    //$blog = new Blog($this->getUser());
    $form = $this->createForm(BlogType::class, $blog);
    $form->submit($request->toArray());

    if ($form->isSubmitted() && $form->isValid()) {

      $this->entityManager->persist($blog);
      $this->entityManager->flush();

      return $this->json($blog);
    } else {
      return $this->json((string)$form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }
  }

  #[Route('/api/blog/{blog}', name: 'api-blog-delete', methods: ['delete'], format: 'json')]
  public function delete(Blog $blog): Response
  {
    $this->entityManager->remove($blog);
    $this->entityManager->flush();

    return $this->json([]);
  }

  #[Route('/api/blog/filter', name: 'api-blog-filter', methods: ['get'], format: 'json')]
  //public function filter(#[MapQueryParameter] int $id): Response {
  public function filter(#[MapQueryString] BlogFilter $blogFilter): Response
  {

    //dd($blogFilter);

    $blogs = $this->blogRepository->findByBlogFilter($blogFilter);

//    $this->entityManager->remove($blog);
//    $this->entityManager->flush();

    return $this->json($blogs->getQuery()->getResult());
  }

  #[Route('/api/blog/dto', name: 'api-blog-add-dto', methods: ['post'], format: 'json')]
  public function addDto(Request $request,  #[MapRequestPayload] BlogDto $blogDto): Response
//                         #[MapRequestPayload(
//                          // acceptFormat: 'json',
//                          // resolver: 'App\Resolver\BlogResolver',
//                         )] BlogDto $blogDto): Response
  {
    //dd($blogDto);
    $blog = Blog::createFromDto($this->getUser(), $blogDto);

    $this->entityManager->persist($blog);
    $this->entityManager->flush();

    return $this->json($blog, Response::HTTP_CREATED);

  }

  #[Route('/api/blog/dto/{blog}', name: 'api-blog-update-dto', methods: ['put'], format: 'json')]
  public function updateDto(Blog $blog, #[MapRequestPayload] BlogDto $blogDto): Response
  {
    $blog = Blog::updateFromDto($blogDto, $blog);
    //$this->entityManager->persist($blog);
    $this->entityManager->flush();

    return $this->json($blog, Response::HTTP_CREATED);
  }

}