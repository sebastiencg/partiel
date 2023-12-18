<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Invitation;
use App\Repository\InvitationRepository;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class InvitationController extends AbstractController
{
    #[Route('/myInvitation', name: 'app_invitation', methods: ["GET"])]
    public function index(InvitationRepository $invitationRepository): Response
    {
        return $this->json($invitationRepository->findBy(["profile" => $this->getUser()->getProfile()]), Response::HTTP_OK, [], ['event' => 'profile:read-one']);
    }

    #[Route('/send/invitation/event/{id}', name: 'app_invitation_send', methods: ["POST"])]
    public function send(Request $request, ProfileRepository $profileRepository, Event $event,EntityManagerInterface $entityManager): Response
    {

        $json = $request->getContent();
        $datas = json_decode($json, true);
        if($this->getUser()->getProfile()==$event->getAuthor()) {
            return $this->json("you are no author of event", Response::HTTP_BAD_REQUEST);
        }
        foreach ($datas as $data) {
            $profileId = $data["profile"];
            $profile = $profileRepository->findOneBy(["id" => $profileId]);
            if (!$profile) {
                return $this->json("ID " . $profileId . " no find", Response::HTTP_BAD_REQUEST);
            }
            foreach ($event->getInvitations() as $eventInvitation) {
                if ($eventInvitation->getProfile() === $profile) {
                    return $this->json("ID " . $profileId . " already invited", Response::HTTP_BAD_REQUEST);
                }
            }

            $invitation=new Invitation();
            $invitation->setProfile($profile);
            $invitation->setAccpetedInvitation(false);
            $invitation->setPrivateEvent($event);
            $entityManager->persist($invitation);
        }
        $entityManager->flush();
        return $this->json("invitations send", Response::HTTP_OK);
    }
}
