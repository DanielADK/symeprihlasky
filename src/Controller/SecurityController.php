<?php

namespace App\Controller;

use App\Repository\PersonRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;

class SecurityController extends AbstractController {

    /**
     * @throws NonUniqueResultException
     */
/*   #[Route('/prihlaseni', name: 'prihlaseni')]
    public function requestLoginLink(NotifierInterface $notifier, LoginLinkHandlerInterface $loginLinkHandler, PersonRepository $userRepository, Request $request): Response {
        // check if Auth form is submitted
        if ($request->isMethod('POST')) {
            // load the user in some way (e.g. using the form input)
            $email = $request->request->get('email');
            $user = $userRepository->findOneByEmail($email);

            // create a Auth link for $user this returns an instance
            // of LoginLinkDetails
            $loginLinkDetails = $loginLinkHandler->createLoginLink($user);

            // create notification based on the Auth link details
            $notification = new LoginLinkNotification(
                $loginLinkDetails,
                'PŘIHLAŠOVACÍ EMAIL'
            );
            $recipient = new Recipient($user->getEmail());

            $notifier->send($notification, $recipient);

            return $this->render("prihlaseni_odeslano.html.twig");

            // ... send the link and return a response (see next section)
        }

        // if it's not submitted, render the "Auth" form
        return $this->render('prihlaseni.html.twig');
    }

    #[Route('/overeni', name: 'overeni')]
    public function check(Request $request): Response
    {
        // get the Auth link query parameters
        $expires = $request->query->get('expires');
        $username = $request->query->get('user');
        $hash = $request->query->get('hash');

        // and render a template with the button
        return $this->render('security/process_login_link.html.twig', [
            'expires' => $expires,
            'user' => $username,
            'hash' => $hash,
        ]);
    }
*/
    /**
     * @Route("/prihlaseni", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error]);
    }

    /**
     * @Route("/odhlaseni", name="logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}