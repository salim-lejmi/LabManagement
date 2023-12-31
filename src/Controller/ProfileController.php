<?php

namespace App\Controller;
use App\Entity\Publication;
use App\Entity\Project;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;

class ProfileController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    #[Route('/profile/{id}', name: 'user_profile')]
    public function index(int $id): Response
    {
       $user = $this->doctrine->getRepository(User::class)->find($id);

       $publications = $this->doctrine->getRepository(Publication::class)->findBy(['author' => $user]);
       $projects = $this->doctrine->getRepository(Project::class)->findBy(['author' => $user]);

       return $this->render('profile/profile.html.twig', [
           'user' => $user,
           'publications' => $publications,
           'projects'=>$projects,
       ]);
    }
    #[Route('/profile/delete', name: 'user_delete')]
    public function deleteAccount(Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, SessionInterface $session): Response
    {
        $user = $this->getUser();
        if($user && $this->isCsrfTokenValid('delete_account', $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            
            $tokenStorage->setToken(null);
            $session->invalidate();
    
            return $this->redirectToRoute('app_accueil');
        }
        return $this->redirectToRoute('user_profile');
    }
}
