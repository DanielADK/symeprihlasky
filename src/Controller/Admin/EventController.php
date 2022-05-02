<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController {

    #[Route('/admin/akce/seznam', name: 'admin_event_list')]
    public function index(): Response {
        return $this->render('Admin/Event/list.html.twig', [
            "section" => "event",
            "page_name" => "Seznam akcí",
            "page_path" => array("Domov", "Akce", "Seznam")
        ]);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/admin/akce/zobrazit/{id}', name: 'admin_event_view')]
    public function view(int $id, EntityManagerInterface $em, Request $request): Response {
        $event = $em->getRepository("App:Event")->findOneBy(
            array("id" => $id)
        );
        if ($event == null) {
            $this->addFlash("warning", "Tato akce nebyla nalezena!");
            return new RedirectResponse($this->generateUrl("admin_event_list"));
        }

        return $this->render('Admin/Event/view.html.twig', [
            "section" => "event",
            "event" => $event,
            "page_name" => "Náhled akce ID: ".$id,
            "page_path" => array("Domov", "Akce", "Náhled akce")
        ]);
    }
}