<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Person;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TCPDF;

class ApplicationController extends AbstractController {
    private function getHeader(TCPDF $pdf): void {
        // Logo -> T.O.Severka
        $pdf->Image('../public/images/logoSEVERKA-CB.png',163,25,30);
        // Logo -> ČTU
        $pdf->Image('../public/images/logoCTU.jpg',18,28,35);
        // Arial bold 15
//        $pdf->AddFont('DejaVuHeader','','DejaVuSerif.ttf',true);
        $pdf->SetFont('dejavuserif','',14);
        // Title
        $pdf->SetXY(10,8);
        $pdf->Cell(192,10,'Č e s k á  t á b o r n i c k á  u n i e','B',0,'C');
        // Line break
        //$pdf->Ln(20);
    }
    private function getFooter(TCPDF $pdf): void {
        // Pozice 1 cm od spoda
        $pdf->SetY(285);
        // Arial italic 8
        $pdf->SetFont('dejavuserifcondensed','I',8);
        // Page number
        $pdf->Cell(0,0,'((Přihláška vytvořena přes systém elektronických přihlášek na webu www.eprihlasky.osadaseverka.cz))',0,0,'R');
    }
    private function addInfecticity(TCPDF $pdf): void {
        $pdf->SetFont('dejavuserifcondensed','',11);
        $pdf->SetXY(15, 210);
        $pdf->Cell(0,10,'Součástí této přihlášky je potvrzení dětského lékaře o způsobilosti dítěte zúčastnit se zotavovací akce',0,0,'L');

        $pdf->SetXY(15, 215);
        $pdf->Cell(0,10,'a potvrzení o bezinfekčnosti, které se předává v den odjezdu na letní tábor a není starší jednoho dne.',0,0,'L');

        $pdf->SetXY(32, 220);
        $pdf->Cell(0,10,'po naplnění stavu 40 dětí na letním táboře si vyhrazujeme právo odmítnout další zájemce.',0,0,'L');

        $pdf->SetFont('dejavuserifcondensed', 'B', 11);
        $pdf->SetXY(15, 220);
        $pdf->Cell(0, 10, 'POZOR:', 0, 0, 'L');
    }
    private function emptyApplication(TCPDF $pdf) {
        $pdf->SetTitle("PRÁZDNÁ PŘÍHLÁŠKA");
        $pdf->SetXY(75, 45);
        $pdf->TextField('event-name', 60, 10, array('alignment'=>'center'));

        $pdf->SetXY(50, 68);
        $pdf->TextField('fullname', 60, 5);
        $pdf->SetXY(50, 73);
        $pdf->TextField('date-of-birth', 30, 5, array("charLimit" => 10));
        $pdf->SetXY(50, 78);
        $pdf->TextField('age', 9, 5, array("charLimit" => 2));
        $pdf->SetXY(50, 83);
        $pdf->TextField('shirt-size', 12, 5, array("charLimit" => 4));
        $pdf->SetXY(135, 67);
        $pdf->TextField('residence-street', 60, 5);
        $pdf->SetXY(135, 73);
        $pdf->TextField('residence-city', 60, 5);
        $pdf->SetXY(135, 77);
        $pdf->TextField('residence-postcode', 20, 5, array("charLimit" => 5));

        $pdf->SetXY(50, 103);
        $pdf->TextField('parent-fullname', 60, 5);
        $pdf->SetXY(50, 108);
        $pdf->TextField('parent-email', 60, 5);
        $pdf->SetXY(50, 113);
        $pdf->TextField('parent-phone', 34, 5, array("charLimit" => 13));
        $pdf->SetXY(135, 103);
        $pdf->TextField('parent-residence-street', 60, 5);
        $pdf->SetXY(135, 108);
        $pdf->TextField('parent-residence-city', 60, 5);
        $pdf->SetXY(135, 113);
        $pdf->TextField('parent-residence-postcode', 20, 5);

        $pdf->SetXY(120, 258);
        $pdf->TextField('sign-city', 38, 5);
        $pdf->SetXY(168, 258);
        $pdf->TextField('sign-date', 31, 5, array("charLimit" => 10));
    }

    /**
     * @throws Exception
     */
    private function fillChild(TCPDF $pdf, object $app) {
        /* Children */
        $pdf->SetTitle($app->getChild()->getFullname()." (".$app->getHash().")");
        $pdf->SetFont('dejavuserifcondensed','B',15);
        $pdf->SetXY(10, 40);
        $pdf->Cell(0,4,$app->getEvent()->getfullName(),0,0,'C');
        $pdf->SetFont('dejavuserifcondensed','B',11);
        $pdf->SetXY(50, 65);
        $pdf->Cell(0,10,$app->getChild()->getFullname(),0,0,'L');
        $pdf->SetXY(50, 70);
        $pdf->Cell(0,10, $app->getChild()->getBirthDate()->format("d.m.Y"), 0,0,'L');
        $pdf->SetXY(50, 75);
        $pdf->Cell(0,10,$app->getChild()->getAgeAtDate(
            $app->getEvent()->getDateStart()).' let',0,0,'L');
        $pdf->SetXY(50, 80);
        $pdf->Cell(0,10, $app->getShirtSize(),0,0,'L');
        $pdf->SetXY(135, 65);
        $pdf->Cell(0,10,$app->getChild()->getAddress()->getStreet(),0,0,'L');
        $pdf->SetXY(135, 70);
        $pdf->Cell(0,10,$app->getChild()->getAddress()->getCity(),0,0,'L');
        $pdf->SetXY(135, 75);
        $pdf->Cell(0,10,$app->getChild()->getAddress()->getPostcode(),0,0,'L');
    }

    /**
     * @throws Exception
     */
    private function fillParent(TCPDF $pdf, object $app) {
        /* Parent */
        $pdf->SetFont('dejavuserifcondensed','B',11);
        $pdf->SetXY(50, 100);
        $pdf->Cell(0,10,$app->getChild()->getParent()->getFullname(),0,0,'L');
        $pdf->SetXY(50, 105);
        $pdf->Cell(0,10, $app->getChild()->getParent()->getEmail(), 0,0,'L');
        $pdf->SetXY(50, 110);
        $pdf->Cell(0,10, $app->getChild()->getParent()->getPhone(),0,0,'L');
        $pdf->SetXY(135, 100);
        $pdf->Cell(0,10,$app->getChild()->getParent()->getAddress()->getStreet(),0,0,'L');
        $pdf->SetXY(135, 105);
        $pdf->Cell(0,10,$app->getChild()->getParent()->getAddress()->getCity(),0,0,'L');
        $pdf->SetXY(135, 110);
        $pdf->Cell(0,10,$app->getChild()->getParent()->getAddress()->getPostcode(),0,0,'L');
    }
    /**
     * @throws Exception
     */
    #[Route('/prihlaska/{hash}', name: 'application_pdf')]
    public function application(Request $request, ManagerRegistry $doctrine, string $hash, bool $regen = false): Response {
        $hash = strtoupper($hash);

        /* If application was already generated */
        if (file_exists(__DIR__."/../../pdf/".$hash.".pdf") && !$regen) {
            return new BinaryFileResponse(__DIR__."/../../pdf/".$hash.".pdf");
        }

        $app = $doctrine->getRepository(Application::class)
            ->findBy(array("hash" => $hash));

        if ($hash != "PRAZDNA") {
            if ($app == null) {
                $this->addFlash("warning", "Tato přihláška neexistuje");
                return new RedirectResponse($this->generateUrl("admin_person_list"));
                // TODO
            } else
                $app = $app[0];
        }

        $pdf = new TCPDF("P", "mm", "A4", true, "UTF-8");
        $pdf->SetAuthor("T.O.Severka - systém E-Přihlášky");
        $pdf->SetSubject('Elektronická přihláška');
        $pdf->SetKeywords('elektronická přihláška, dětský, letní, tábor, T. O. Severka');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setAutoPageBreak(false, PDF_MARGIN_BOTTOM);

        $pdf->AddPage();
        $this->getHeader($pdf);
        $this->getFooter($pdf);
        $this->addInfecticity($pdf);
        if ($hash == "PRAZDNA")
            $this->emptyApplication($pdf);
        else {
            $this->fillChild($pdf, $app);
            $this->fillParent($pdf, $app);

        }

        $pdf->SetFont('dejavuserifcondensed', '', 14);
        $pdf->SetXY(10, 35);
        $pdf->Cell(0, 0, 'Přihláška na ', 0, 0, 'C');

        $pdf->SetFont('dejavuserifcondensed', 'B', 14);
        $pdf->SetXY(10, 60);
        $pdf->Cell(0, 4, 'Informace o dítěti', 'B', 0, 'L');
        $pdf->SetXY(10, 95);
        $pdf->Cell(0, 4, 'Informace o zákonném zástupci', 'B', 0, 'L');
        $pdf->SetXY(10, 125);
        $pdf->Cell(0, 4, 'Další informace', 'B', 0, 'L');

        //--------Dítě--------//
        $pdf->SetFont('dejavuserifcondensed', '', 11);
        $pdf->SetXY(15, 65);
        $pdf->Cell(0, 10, 'Jméno a příjmení:', 0, 0, 'L');
        $pdf->SetXY(15, 70);
        $pdf->Cell(0, 10, 'Datum narození:', 0, 0, 'L');
        $pdf->SetXY(15, 75);
        $pdf->Cell(0, 10, 'Věk:', 0, 0, 'L');
        $pdf->SetXY(15, 80);
        $pdf->Cell(0, 10, 'Velikost trička:', 0, 0, 'L');
        $pdf->SetXY(115, 65);
        $pdf->Cell(0, 10, 'Bydliště:', 0, 0, 'L');

        //--------Zákonný zástupce--------//
        $pdf->SetFont('dejavuserifcondensed', '', 11);
        $pdf->SetXY(15, 100);
        $pdf->Cell(0, 10, 'Jméno a příjmení:', 0, 0, 'L');
        $pdf->SetXY(15, 105);
        $pdf->Cell(0, 10, 'E-mail:', 0, 0, 'L');
        $pdf->SetXY(15, 110);
        $pdf->Cell(0, 10, 'Telefon:', 0, 0, 'L');
        $pdf->SetXY(115, 100);
        $pdf->Cell(0, 10, 'Bydliště:', 0, 0, 'L');

        //Další informace Struktura - TLUSTE
        $pdf->SetFont('dejavuserifcondensed', 'B', 11);
        $pdf->SetXY(15, 130);
        $pdf->Cell(0, 10, 'Místo konání:', 0, 0, 'L');
        $pdf->SetXY(15, 135);
        $pdf->Cell(0, 10, 'Pořadatel:', 0, 0, 'L');
        $pdf->SetXY(15, 140);
        $pdf->Cell(0, 10, 'IČ:', 0, 0, 'L');

        //Další informace Struktura - tenke
        $pdf->SetFont('dejavuserifcondensed', '', 11);
        $pdf->SetXY(50, 130);
        $pdf->Cell(0, 10, 'Černousy u Frýdlantu', 0, 0, 'L');
        $pdf->SetXY(50, 135);
        $pdf->Cell(0, 10, 'T.O.Severka p.s., ČTU z.s.', 0, 0, 'L');
        $pdf->SetXY(50, 140);
        $pdf->Cell(0, 10, '70925542', 0, 0, 'L');

        $pdf->SetXY(15, 230);
        $pdf->Cell(0, 10, '-> Podpisem souhlasíte s výše psanými informacemi.', 0, 0, 'L');
        $pdf->SetXY(15, 235);
        $pdf->Cell(
            0,
            10,
            '-> Podpisem souhlasíte s využitím osobním údajům (vypsané na webových stránkách pod ozn. "GDPR")',
            0,
            0,
            'L'
        );
        $pdf->SetXY(20, 255);
        $pdf->Cell(0, 10, 'V: ______________________ dne: __________________', 0, 0, 'R');
        $pdf->SetXY(20, 270);
        $pdf->Cell(0,10,'Podpis zákonného zástupce: ______________________',0,0,'R');

        $pdf->Output(__DIR__."/../../pdf/".$hash.".pdf", "F");

        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf; charset=UTF-8'));
    }

    /**
     * @throws Exception
     */
    #[Route('/prihlaska/{hash}/regen', name: 'application_pdf_regen')]
    public function regenApplication(Request $request, ManagerRegistry $doctrine, string $hash): Response {
        return $this->application($request, $doctrine, $hash, true);
    }
}