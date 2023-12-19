<?php

namespace App\Controller;

use App\Entity\Contributions;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/contribution')]
class ContributionsController extends AbstractController
{
    #[Route('/suggestion/{id}', name: 'app_contribution_suggestion', methods: ['POST'])]
    public function suggestion(Event $event,Request $request,SerializerInterface $serializer,EntityManagerInterface $entityManager): Response
    {
        if ($event->getAuthor()!==$this->getUser()->getProfile()){
            return $this->json("you are not the author of the event to create a suggestion", Response::HTTP_BAD_REQUEST);
        }

        $json = $request->getContent();
        $contribution = $serializer->deserialize($json, Contributions::class, 'json');
        $contribution->setEvent($event);
        $contribution->setSuggestion(true);
        $entityManager->persist($contribution);
        $entityManager->flush();
        return $this->json($contribution,Response::HTTP_OK,[],['groups'=>'contribution:read-all']);
    }
    #[Route('/supported/{id}', name: 'app_contribution_supported', methods: ['GET'])]
    public function supported(Contributions $contribution,EntityManagerInterface $entityManager): Response
    {
        if (!in_array($this->getUser()->getProfile(),$contribution->getEvent()->getParticipants()->getValues())){
            return $this->json(["message"=>"this is a private event sorry"],Response::HTTP_OK);
        }
        if ($contribution->getContributor()!==null){
            return $this->json(["message"=>"there is already a contributor"],Response::HTTP_OK);
        }
        $contribution->setContributor($this->getUser()->getProfile());
        $entityManager->persist($contribution);
        $entityManager->flush();
        return $this->json($contribution,Response::HTTP_OK,[],['groups'=>'contribution:read-all']);
    }

    #[Route('/event/{id}', name: 'app_contribution_all', methods: ['GET'])]
    public function allContribution(Event $event): Response
    {
        return $this->json($event->getContributions(),Response::HTTP_OK,[],['groups'=>'contribution:read-all']);
    }

    #[Route('/delete/{id}', name: 'app_contribution_remove', methods: ['DELETE'])]
    public function removeContribution(Contributions $contribution,EntityManagerInterface $entityManager): Response
    {
        if ($contribution->getEvent()->getAuthor()!==$this->getUser()->getProfile()){
            return $this->json("you are not the author of the event to create a suggestion", Response::HTTP_BAD_REQUEST);
        }
        $entityManager->remove($contribution);
        $entityManager->flush();
        return $this->json("contribution delete", Response::HTTP_BAD_REQUEST);

    }
    #[Route('/{id}/mySupported', name: 'app_contribution_mySupported', methods: ['POST'])]
    public function mySupported(Event $event,Request $request,SerializerInterface $serializer,EntityManagerInterface $entityManager): Response
    {
        if (!in_array($this->getUser()->getProfile(),$event->getParticipants()->getValues())){
            return $this->json(["message"=>"this is a private event sorry"],Response::HTTP_OK);
        }
        $contribution=[];
        foreach ($event->getContributions()->getValues() as $value){
            if ($value->getContributor()==$this->getUser()->getProfile()){
                $contribution[] = $value;
            }
        }
        return $this->json($contribution,Response::HTTP_OK,[],['groups'=>'contribution:read-all']);
    }

}
