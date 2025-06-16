<?php
namespace App\Controller;

use App\Entity\Habit;
use App\Form\HabitForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/habit')]
#[isGranted('ROLE_USER')]
final class HabitController extends AbstractController
{
    #[Route('', name: 'dashboard')]
    public function home(): Response
    {
       return $this->render('habit/index.html.twig');
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
            return $this->redirectToRoute('dashboard');  // Redirect after success
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
        return $this->redirectToRoute('dashboard');
    }



    #[Route('/add_habit', name: 'app_add_habit')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $habit = new Habit();
        $form = $this->createForm(HabitForm::class, $habit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $habit->setColor($request->request->get('color'));
            $habit->setUser($this->getUser());

            $entityManager->persist($habit);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('habit/addHabit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
