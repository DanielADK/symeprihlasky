<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
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
     * @throws NonUniqueResultException
     */
    #[Route('/admin/akce/zobrazit/{shortName}', name: 'admin_event_view')]
    public function view(string $shortName, ManagerRegistry $doctrine, Request $request): Response {
        $shortName = strtoupper($shortName);
        $event = $doctrine->getRepository(Event::class)->findOneBy(array("shortName" => $shortName));

        if ($event == null) {
            $this->addFlash("warning", "Tato akce nebyla nalezena!");
            return new RedirectResponse($this->generateUrl("admin_event_list"));
        }

        return $this->render('Admin/Event/view.html.twig', [
            "section" => "event",
            "event" => $event,
            "page_name" => "Náhled akce: ".$event->getshortName(),
            "page_path" => array("Domov", "Akce", "Náhled akce", $event->getshortName())
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/admin/akce/upravit/{shortName}', name: 'admin_event_edit')]
    public function edit(string $shortName, ManagerRegistry $doctrine, Request $request): Response {
        $shortName = strtoupper($shortName);
        $event = $doctrine->getRepository(Event::class)->findOneBy(array("shortName" => $shortName));

        if ($event == null) {
            $this->addFlash("warning", "Tato akce nebyla nalezena!");
            return new RedirectResponse($this->generateUrl("admin_event_list"));
        }

        return $this->render('Admin/Event/edit.html.twig', [
            "section" => "event",
            "event" => $event,
            "page_name" => "Úprava akce: ".$event->getshortName(),
            "page_path" => array("Domov", "Akce", "Úprava akce", $event->getshortName())
        ]);
    }
}