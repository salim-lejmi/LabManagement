<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Researcher;

#[Route('/project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_project', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Set the author of the project
            $project->setAuthor($this->getUser());
    
            $entityManager->persist($project);
            foreach ($project->getEquipments() as $equipment) {
                $equipment->addProject($project);
                $entityManager->persist($equipment);
                }
            $entityManager->flush();
    
            return $this->redirectToRoute('app_project', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_project', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_project_delete')]
    public function delete(Project $project, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($project);
            $entityManager->flush();
        
    
        return $this->redirectToRoute('app_project');
    }


    
}