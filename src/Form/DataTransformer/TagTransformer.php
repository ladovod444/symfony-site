<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\PersistentCollection;

class TagTransformer implements DataTransformerInterface
{
  public function __construct(
    private TagRepository $tagRepository
  ) {
  }

  /**
   * Transforms an object (issue) to a string (number).
   *
   * @param  ArrayCollection|PersistentCollection|null $tags
   */
  public function transform($tags): string
  {
    if (null === $tags) {
      return '';
    }

    //dd($tag);
    $array = [];
    foreach ($tags as $tag) {
      /**
       * @var $tag Tag
       */
      $value = $tag->getName();
      $array[] = $value;
    }

    return implode(', ', $array);
  }

  /**
   * Transforms a string (number) to an object (issue).
   *
   * @param  string $issueNumber
   * @throws TransformationFailedException if object (issue) is not found.
   */
  public function reverseTransform(mixed $value): ArrayCollection
  {
    // no issue number? It's optional, so that's ok
    if (!$value) {
      return new ArrayCollection();
    }

    //dd($tagNumber);

    $items = explode(',', $value);
    $items = array_map('trim', $items);
    $items = array_unique($items);

    $tags = new ArrayCollection();

    foreach ($items as $item) {

      $tag = $this->tagRepository->findOneBy(['name' => $item]);

      if (!$tag) {
        $tag = (new Tag())->setName($item);
      }

      $tags->add($tag);
    }

    return $tags;
  }
}