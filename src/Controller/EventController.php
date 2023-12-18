<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;



#[Route('/api/event')]
class EventController extends AbstractController
{
    #[Route('/join/{id}', name: 'app_event_join', methods: ['GET'])]
    public function join(Event $event,EntityManagerInterface $entityManager): Response
    {
        if ($event->getStatut()==="prive"){
            return $this->json(["message"=>"this is a private event sorry"],Response::HTTP_OK,[],['groups'=>'event:read-all']);
        }
        $event->addParticipant($this->getUser()->getProfile());
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->json("you joined the event",Response::HTTP_OK,[],['groups'=>'event:read-all']);
    }

    #[Route('/public/', name: 'app_event_all', methods: ['GET'])]
    public function allEventPublic(EventRepository $eventRepository): Response
    {
        return $this->json($eventRepository->findBy(["statut"=>"public"]),Response::HTTP_OK,[],['groups'=>'event:read-one']);
    }

    #[Route('/mine', name: 'app_event_mine', methods: ['GET'])]
    public function eventMine(EventRepository $eventRepository): Response
    {
        return $this->json($eventRepository->findByParticipants($this->getUser()->getProfile()),Response::HTTP_OK,[],['groups'=>'event:read-one']);
    }
    #[Route('/new', name: 'app_event_newEvent', methods: ['POST'])]
    public function newEvent(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $json = $request->getContent();
        $event = $serializer->deserialize($json, Event::class, 'json');
        if (empty($event->getPlace()) || empty($event->getDescription() || empty($event->getPlaceType()) || empty($event->getDateInit())) || empty($event->getDateEnd() || empty($event->getStatut())) ){
            return $this->json(['error' => 'bad request'], Response::HTTP_BAD_REQUEST);
        }
        if ($event->getStatut()!=="prive" && $event->getStatut()!=="public"){
            return $this->json(['error' => 'bad  statut send (prive or public)'], Response::HTTP_BAD_REQUEST);
        }
        $dt = new \DateTime();
        if ($event->getDateInit()<$dt){
            return $this->json(['error' => 'bad dateInit'], Response::HTTP_BAD_REQUEST);
        }
        if ($event->getDateInit()>$event->getDateEnd()){
            return $this->json(['error' => 'bad dateEnd'], Response::HTTP_BAD_REQUEST);
        }
        $event->setAuthor($this->getUser()->getProfile());
        $event->addParticipant($this->getUser()->getProfile());
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->json($event,Response::HTTP_OK,[],['groups'=>'event:read-all']);

    }
    #[Route('/{id}', name: 'app_event', methods: ['GET'])]
    public function event(Event $event): Response
    {
        if(!in_array($this->getUser()->getProfile(),$event->getParticipants()->getValues())){
            return $this->json(['error' => 'you are not part of the event'], Response::HTTP_BAD_REQUEST);
        }
        return $this->json($event,Response::HTTP_OK,[],['groups'=>'event:read-one']);
    }

}
