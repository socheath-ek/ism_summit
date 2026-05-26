<?php
namespace App\Controller;

use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use App\Repository\SummitRepository;
use App\Service\ExcelExportService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(SummitRepository $summitRepository): Response
    {
        $summits = $summitRepository->findAll();
        $activeSummit = $summitRepository->findActiveSummit();
        return $this->render('admin/dashboard.html.twig', [
            'summits' => $summits,
            'activeSummit' => $activeSummit,
        ]);
    }

    #[Route('/registrations', name: 'app_admin_registrations')]
    public function registrations(
        Request $request,
        RegistrationRepository $registrationRepository,
        SummitRepository $summitRepository
    ): Response {
        $activeSummit = $summitRepository->findActiveSummit();
        $sort = $request->query->get('sort', 'registeredAt');
        $dir = $request->query->get('dir', 'DESC');

        $registrations = $activeSummit
            ? $registrationRepository->findBySummitSorted($activeSummit->getId(), $sort, $dir)
            : [];

        return $this->render('admin/registrations.html.twig', [
            'registrations' => $registrations,
            'activeSummit' => $activeSummit,
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    #[Route('/registrations/export/excel', name: 'app_admin_export_excel')]
    public function exportExcel(
        RegistrationRepository $registrationRepository,
        SummitRepository $summitRepository,
        ExcelExportService $excelService
    ): Response {
        $summit = $summitRepository->findActiveSummit();
        $registrations = $summit ? $registrationRepository->findBy(['summit' => $summit]) : [];
        $file = $excelService->exportRegistrations($registrations, $summit?->getCity() ?? 'Summit');

        return (new BinaryFileResponse($file))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'registrations.xlsx')
            ->deleteFileAfterSend(true);
    }

    #[Route('/registrations/{id}/cancel', name: 'app_admin_cancel_registration', methods: ['POST'])]
    public function cancelRegistration(
        Registration $registration,
        EntityManagerInterface $em
    ): Response {
        $registration->setStatus('cancelled');
        $em->flush();
        $this->addFlash('success', 'Registration cancelled.');
        return $this->redirectToRoute('app_admin_registrations');
    }

    #[Route('/registrations/{id}/delete', name: 'app_admin_delete_registration', methods: ['POST'])]
    public function deleteRegistration(
        Registration $registration,
        EntityManagerInterface $em
    ): Response {
        $em->remove($registration);
        $em->flush();
        $this->addFlash('success', 'Registration deleted.');
        return $this->redirectToRoute('app_admin_registrations');
    }
}