<?php

namespace App\Security;

use App\Entity\Blog;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BlogVoter extends Voter
{
  // these strings are just invented: you can use anything
  const VIEW = 'view';
  const EDIT = 'edit';

  public function __construct(private Security $security) {

  }

  protected function supports(string $attribute, mixed $subject): bool
  {
    // if the attribute isn't one we support, return false
    if (!in_array($attribute, [self::VIEW, self::EDIT])) {
      return false;
    }

    // only vote on `Blog` objects
    if (!$subject instanceof Blog) {
      return false;
    }

    return true;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
  {
    $user = $token->getUser();

    if (!$user instanceof User) {
      // the user must be logged in; if not, deny access
      return false;
    }

    // you know $subject is a Blog object, thanks to `supports()`
    /** @var Blog $blog */
    $blog = $subject;

    return match($attribute) {
      self::VIEW => $this->canView($blog, $user),
      self::EDIT => $this->canEdit($blog, $user),
      default => throw new \LogicException('This code should not be reached!')
    };
  }

  private function canView(Blog $blog, User $user): bool
  {
    //return false;
    // if they can edit, they can view
    if ($this->canEdit($blog, $user)) {
      return true;
    }

    // the Blog object could have, for example, a method `isPrivate()`
    //return !$blog->isPrivate();
    return true;
  }

  private function canEdit(Blog $blog, User $user): bool
  {
    // Нужно чтобы админ имел права на редактирование всех постов
    if ($this->security->isGranted('ROLE_ADMIN')) {
      return true;
    }
    //return true;
    //return false;
    // this assumes that the Blog object has a `getUser()` method
    return $user === $blog->getUser();
  }
}