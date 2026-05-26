<?php
namespace App\Controller;

use App\Entity\Registration;
use App\Form\RegistrationFormType;
use App\Repository\RegistrationRepository;
use App\Repository\SummitRepository;
use App\Service\PdfTicketService;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SummitRepository $summitRepository): Response
    {
        $summit = $summitRepository->findActiveSummit();
        return $this->render('home/index.html.twig', ['summit' => $summit]);
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        SummitRepository $summitRepository,
        RegistrationService $registrationService
    ): Response {
        $summit = $summitRepository->findActiveSummit();

        if (!$summit) {
            $this->addFlash('error', 'No active summit found.');
            return $this->redirectToRoute('app_home');
        }

        if ($summit->isFull()) {
            $this->addFlash('warning', 'Sorry, this summit is fully booked.');
            return $this->redirectToRoute('app_home');
        }

        $registration = new Registration();
        $form = $this->createForm(RegistrationFormType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $success = $registrationService->registerGuest($registration, $summit);
            if ($success) {
                return $this->redirectToRoute('app_register_success', [
                    'ticketNumber' => $registration->getTicketNumber()
                ]);
            }
            $this->addFlash('error', 'Registration failed. Summit may be full.');
        }

        return $this->render('home/register.html.twig', [
            'form' => $form->createView(),
            'summit' => $summit,
        ]);
    }

    #[Route('/register/success/{ticketNumber}', name: 'app_register_success')]
    public function success(
        string $ticketNumber,
        RegistrationRepository $registrationRepository
    ): Response {
        $registration = $registrationRepository->findOneBy(['ticketNumber' => $ticketNumber]);
        if (!$registration) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/success.html.twig', ['registration' => $registration]);
    }

    #[Route('/ticket/download/{ticketNumber}', name: 'app_ticket_download')]
    public function downloadTicket(
        string $ticketNumber,
        RegistrationRepository $registrationRepository,
        PdfTicketService $pdfTicketService
    ): Response {
        $registration = $registrationRepository->findOneBy(['ticketNumber' => $ticketNumber]);
        if (!$registration) throw $this->createNotFoundException();

        $pdf = $pdfTicketService->generateTicketPdf($registration);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="ticket-' . $ticketNumber . '.pdf"'
        ]);
    }

    #[Route('/register/check-capacity', name: 'app_check_capacity', methods: ['GET'])]
    public function checkCapacity(SummitRepository $summitRepository): Response
    {
        $summit = $summitRepository->findActiveSummit();
        return $this->json([
            'remaining' => $summit ? $summit->getRemainingCapacity() : 0,
            'isFull' => $summit ? $summit->isFull() : true,
        ]);
    }
}