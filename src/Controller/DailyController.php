<?php
// src/Controller/DailyController.php
namespace App\Controller;

use App\Entity\DailyReport;
use App\Entity\Habit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DailyController extends AbstractController
{
    #[Route('/daily', name: 'app_daily')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $today = new \DateTime('today');

        // Fetch user's habits
        $habits = $em->getRepository(Habit::class)->findBy(['user' => $user]);

        // Fetch existing daily reports for today
        $existingReports = $em->getRepository(DailyReport::class)->findBy([
            'user' => $user,
            'date' => $today,
        ]);

        // Map existing reports by habit id for quick lookup
        $reportsByHabit = [];
        foreach ($existingReports as $report) {
            $reportsByHabit[$report->getHabit()->getId()] = $report;
        }

        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            foreach ($habits as $habit) {
                $habitId = $habit->getId();

                // Retrieve submitted data safely, default to 'none' or empty string
                $status = $data['status'][$habitId] ?? 'none'; // e.g. 'yes', 'no', 'none'
                $comment = $data['comment'][$habitId] ?? '';
                $rating = $data['rating'][$habitId] ?? null;

                if (isset($reportsByHabit[$habitId])) {
                    $report = $reportsByHabit[$habitId];
                } else {
                    $report = new DailyReport();
                    $report->setUser($user);
                    $report->setHabit($habit);
                    $report->setDate($today);
                    $em->persist($report);
                }

                $report->setStatus($status);
                $report->setComment($comment);

                // Assuming you have setRating method in DailyReport entity
                if ($rating !== null) {
                    $report->setRating($rating);
                }
            }

            $em->flush();

            $this->addFlash('success', 'Daily reports updated successfully.');

            // Redirect to avoid form resubmission on refresh
            return $this->redirectToRoute('app_daily');
        }

        // Pass data to template
        return $this->render('daily/index.html.twig', [
            'habits' => $habits,
            'reportsByHabit' => $reportsByHabit,
            'today' => $today,
        ]);
    }
}
