<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
  #[Route('/login', name: 'app_login')]
  public function index(AuthenticationUtils $authenticationUtils): Response
  {
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('login/index.html.twig', [
      // -             'controller_name' => 'LoginController',
      'last_username' => $lastUsername,
      'error' => $error,
    ]);
  }

  /**
   * @param Security $security
   * @return Response
   *
   * Редирект app_login_redirect в зависимости от роли
   * если админ, то на страницу блогов админ /admin/blog
   * если обычный юзер, то на страницу блого юзера /user/blog
   */
  #[Route('/login/redirect', name: 'app_login_redirect')]
  public function redirectUser(Security $security): Response
  {
    if ($security->isGranted('ROLE_ADMIN')) {
      return $this->redirectToRoute('app_blog_index');
    } else {
      return $this->redirectToRoute('app_user_blog_index');
    }
    //
    //dd($this->getUser());
    //dd($this->getUser()->getRoles());
  }
}
