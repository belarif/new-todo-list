<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function loginAction(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @throws Exception
     *
     * @codeCoverageIgnore
     */
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/access_denied', name: 'app_access_denied', methods: ['GET'])]
    public function accessDeniedHandler(): Response
    {
        return $this->render('error/access_denied.html.twig', [
            'response' => 'Vous n’êtes pas autorisé à accéder à cette page',
        ]);
    }
}
