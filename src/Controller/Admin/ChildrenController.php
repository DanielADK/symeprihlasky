<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Child;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChildrenController extends AbstractController {
    #[Route('/admin/dite/seznam', name: 'admin_child_list')]
    public function index(): Response {
        return $this->render('Admin/Children/list.html.twig', [
            "section" => "child",
            "page_name" => "Seznam dětí",
            "page_path" => array("Domov", "Děti", "Seznam")
        ]);
    }
    #[Route('/admin/dite/zobrazit/{id}', name: 'admin_child_view')]
    public function view(int $id, EntityManagerInterface $em, Request $request): Response {
        $child = $em->getRepository(Child::class)->findOneBy(array("id" => $id));

        if ($child == null) {
            $this->addFlash("warning", "Toto dítě nebylo nalezena!");
            return new RedirectResponse($this->generateUrl("admin_child_list"));
        }
//        $child->getParent()->getAddress();
//        $child->getAddress()->getCity();

        return $this->render('Admin/Children/view.html.twig', [
            "section" => "children",
            "child" => $child,
            "page_name" => "Náhled akce ID: ".$id,
            "page_path" => array("Domov", "Akce", "Náhled akce")
        ]);
    }
    /**
     */
    #[Route('/admin/dite/upravit/{id}', name: 'admin_child_edit')]
    public function edit(int $id, EntityManagerInterface $em, Request $request): Response {
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