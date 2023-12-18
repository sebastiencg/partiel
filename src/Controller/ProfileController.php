<?php

namespace App\Controller;

use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(): Response
    {
        return $this->json($this->getUser()->getProfile(),200,[],['groups'=>'profile:read-one']);
    }
    #[Route('/allProfile/', name: 'app_profile_all', methods: ['GET'])]
    public function allProfile(ProfileRepository $profileRepository): Response
    {
        return $this->json($profileRepository->findBy(["displayName"=>true]),Response::HTTP_OK,[],['groups'=>'profile:read-all']);
    }
}
