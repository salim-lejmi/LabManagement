<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ResearcherRepository;
use App\Entity\Project;
use App\Entity\Researcher;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;



class ResearcherController extends AbstractController
{
    #[Route('/researcher', name: 'app_researcher')]
    public function index(ResearcherRepository $researcherRepository): Response
    {
        $researchers = $researcherRepository->findAll();
 
        return $this->render('researcher/index.html.twig', [
            'researchers' => $researchers,
        ]);
    }

    #[Route('/researcher/{id}/assign-project/{projectId}', name: 'app_researcher_assign_project', methods: ['GET', 'POST'])]
public function assignProject(int $id, int $projectId, Request $request, EntityManagerInterface $em): Response
{
   if ($request->getMethod() !== 'POST') {
       throw $this->createAccessDeniedException('Invalid request method');
   }

   $researcher = $em->getRepository(Researcher::class)->find($id);
   $project = $em->getRepository(Project::class)->find($projectId);

   if (!$researcher || !$project) {
       throw $this->createNotFoundException('Researcher or Project not found');
   }

   $researcherProject = new ResearcherProject();
   $researcherProject->setResearcher($researcher);
   $researcherProject->setProject($project);

   $em->persist($researcherProject);
   $em->flush();

   return $this->redirectToRoute('researcher', ['id' => $id]);
}

    
}
