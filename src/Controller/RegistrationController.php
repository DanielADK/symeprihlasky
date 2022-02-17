<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController {

    #[Route('/registrace', name: 'registration')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new Person();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get("plainPassword")->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            /* TODO: Sending email */
            return $this->redirectToRoute("registration");
        }

        return $this->render("Auth/register.html.twig", [
            "registrationForm" => $form->createView(),
        ]);
    }

}