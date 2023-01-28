<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login')]
    public function index(#[CurrentUser] ?Person $user): Response {
        if ($user === null) {
            return $this->json([
                "message" => "missing credentials",
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = "dummy";

        return $this->json([
            "user" => $user->getUserIdentifier(),
            "token" => $token,
        ]);
    }
}
