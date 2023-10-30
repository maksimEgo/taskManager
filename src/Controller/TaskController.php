<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Entity\Users;
use App\Repository\TasksRepository;
use App\Form\AddNewTask;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;

class TaskController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private TokenStorageInterface $tokenStorage,
        private LoginController $controller,
        private AuthenticationUtils $authenticationUtils,
    ){
    }

    #[Route('/', name: 'homepage')]
    public function index(TasksRepository $tasksRepository): Response
    {
        $token = $this->tokenStorage->getToken();

        if ($token && $token->getUser() instanceof Users) {
            return $this->render('task/index.html.twig', [
                'User' => $token->getUser() ,
                'Tasks' => $tasksRepository->findByUser($token->getUser() ),
            ]);
        } else {
            return $this->controller->index($this->authenticationUtils);
        }
    }


    #[Route('/addTask', name: 'add_task')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function newTask(Request $request): Response
    {
            $task = new Tasks();
            $task->setStatus('Active');

            $form = $this->createForm(AddNewTask::class, $task);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $task->setUserId($this->tokenStorage->getToken()->getUser());
                $this->entityManager->persist($task);
                $this->entityManager->flush();

                return $this->redirectToRoute('homepage');
            }

            return $this->render('task/add.html.twig', [
                'form_add' => $form->createView(),
            ]);
        }
}