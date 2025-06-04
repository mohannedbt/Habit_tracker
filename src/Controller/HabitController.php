<?php
namespace App\Controller;

use App\Entity\Habit;
use App\Form\HabitForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/habit')]
final class HabitController extends AbstractController
{
    #[Route('', name: 'dashboard')]
    public function home(EntityManagerInterface $em): Response
    {
        $habits = $em->getRepository(Habit::class)->findAll();

        $events = [];
        $habitList = [];

        foreach ($habits as $habit) {
            if ($habit) {
                $startDate = $habit->getStartDate();

                if ($startDate !== null) {
                    $endDate = (clone $startDate)->modify('+' . ($habit->getPeriod() - 1) . ' days');

                    $events[] = [
                        'title' => $habit->getName(),
                        'start' => $startDate->format('Y-m-d'),
                        'end' => $endDate->modify('+1 day')->format('Y-m-d'),
                        'description' => $habit->getDescription(),
                        'color' => $habit->getColor() ?: '#000000', // fallback black
                    ];

                    // Use stored color for list (no random)
                    $habitList[] = [
                        'id' => $habit->getId(),
                        'name' => $habit->getName(),
                        'color' => $habit->getColor() ?: '#000000',
                    ];
                }
            }
        }

        // Get logged-in user, can be null if not logged in
        $user = $this->getUser();

        return $this->render('habit/index.html.twig', [
            'events' => $events,
            'habitList' => $habitList,
            'user' => $user,
        ]);
    }

    #[Route('/edit_habit/{id}', name: 'app_edit_habit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $habit = $em->getRepository(Habit::class)->find($id);

        if (!$habit) {
            throw $this->createNotFoundException('Habit not found');
        }

        $form = $this->createForm(HabitForm::class, $habit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();  // Save changes to DB
            return $this->redirectToRoute('home');  // Redirect after success
        }

        return $this->render('habit/editHabit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/delete_habit/{id}', name: 'app_delete_habit')]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $habit = $em->getRepository(Habit::class)->find($id);
        if (!$habit) {
            throw $this->createNotFoundException('Habit not found');
        }
        $em->remove($habit);
        $em->flush();
        return $this->redirectToRoute('home');
    }



    #[Route('/add_habit', name: 'app_add_habit')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $habit = new Habit();
        $form = $this->createForm(HabitForm::class, $habit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $colors = ['#FFCDD2', '#C8E6C9', '#BBDEFB', '#FFF9C4', '#D1C4E9', '#FFE0B2'];
            $randomColor = $colors[array_rand($colors)];
            $habit->setColor($randomColor);

            $entityManager->persist($habit);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('habit/addHabit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
