<?php

namespace App\Controller\Api;

use App\Dto\PageDto;
use App\Entity\Page;
use App\Filter\PageFilter;
use App\Form\PageType;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Page")]
class PageController extends AbstractController
{

  public function __construct(private readonly PageRepository         $pageRepository,
                              private readonly UserRepository         $userRepository,
                              private readonly EntityManagerInterface $entityManager)
  {

  }

  #[Route('/api/page', name: 'api-page', methods: ['GET'], format: 'json')]
  #[OA\Response(
    response: 200,
    description: 'Returns the pages, ye!',
    content: new OA\JsonContent(
      type: 'array',
      items: new OA\Items(ref: new Model(type: Page::class, groups: ['full']))
    )
  )]
//  #[OA\Parameter(
//    name: 'order',
//    in: 'query',
//    description: 'The field used to order rewards',
//    schema: new OA\Schema(type: 'string')
//  )]
  public function index(): Response
  {
    $pages = $this->pageRepository->findAll();

    return $this->json(
      $pages,
      context: [
//        AbstractNormalizer::IGNORED_ATTRIBUTES => ['id', 'title']
        // Делим нормализацию по группам
        AbstractNormalizer::GROUPS => ['only_api_page'], // будут выводиться только поля помеченные этой группой
      ],
    );

  }

  #[Route('/api/page', name: 'api-page-add', methods: ['post'], format: 'json')]
  #[OA\Response(
    response: 201,
    description: 'Create the page, ye!',
    content: new Model(type: Page::class, groups: ['only_doc_page'])
  )]
  public function add(Request $request): Response
  {
    //$content = json_decode($request->getContent());
    //dd($content);

    // ! Возможный вариант создания Page через форму
    $page = new Page($this->getUser());
    $form = $this->createForm(PageType::class, $page);
    $form->submit($request->toArray());
    //$form->submit($request->getContent());

    if ($form->isSubmitted() && $form->isValid()) {

      $this->entityManager->persist($page);
      $this->entityManager->flush();

      return $this->json($page, Response::HTTP_CREATED);
    } else {
      return $this->json((string)$form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }

  }

  #[Route('/api/page/{page}', name: 'api-page-update', methods: ['put'], format: 'json')]
  //#[Route('/api/page/{page}', name: 'api-page-update', methods: ['patch'], format: 'json')]
  #[OA\Response(
    response: 200,
    description: 'Update the page, ye!',
    content: new Model(type: PageDto::class, groups: ['only_doc_page'])
  )]
  public function update(page $page, Request $request): Response
  {
    //$page = new page($this->getUser());
    $form = $this->createForm(PageType::class, $page);
    $form->submit($request->toArray());

    if ($form->isSubmitted() && $form->isValid()) {

      $this->entityManager->persist($page);
      $this->entityManager->flush();

      return $this->json($page, Response::HTTP_OK);
    } else {
      return $this->json((string)$form->getErrors(true, false), Response::HTTP_BAD_REQUEST);
    }
  }

  #[Route('/api/page/{page}', name: 'api-page-delete', methods: ['delete'], format: 'json')]
  #[OA\Response(
    response: 204,
    description: 'Delete the page, ye!',
    //content: new Model(type: PageDto::class, groups: ['only_doc_page'])
  )]
  public function delete(Page $page): Response
  {
    $this->entityManager->remove($page);
    $this->entityManager->flush();

    return $this->json([], Response::HTTP_NO_CONTENT);
  }

  #[Route('/api/page/filter', name: 'api-page-filter', methods: ['get'], format: 'json')]
  public function filter(#[MapQueryString] PageFilter $pageFilter): Response
  {
    //dd($pageFilter);
    $pages = $this->pageRepository->findByPageFilter($pageFilter);
    return $this->json($pages->getQuery()->getResult());
  }

  #[Route('/api/page/dto', name: 'api-page-add-dto', methods: ['post'], format: 'json')]
  #[OA\Response(
    response: 201,
    description: 'Create the page, ye!',
    content: new Model(type: PageDto::class, groups: ['only_doc_page'])
  )]
  public function addDto(Request $request, #[MapRequestPayload] PageDto $pageDto): Response
  {

    $page = Page::createFromDto($this->getUser(), $pageDto);
    $this->entityManager->persist($page);
    $this->entityManager->flush();

    return $this->json($page, Response::HTTP_CREATED);
  }

  #[Route('/api/page/dto/{page}', name: 'api-page-update-dto', methods: ['put'], format: 'json')]
  #[OA\Response(
    response: 200,
    description: 'Update the page DTO, ye!',
    content: new Model(type: PageDto::class, groups: ['only_doc_page'])
  )]
  public function dto(#[MapRequestPayload] PageDto $pageDto, Page $page): Response
  {
    Page::updateFromDto($pageDto, $page);
    $this->entityManager->flush();
    return $this->json($page, Response::HTTP_OK);
  }

}