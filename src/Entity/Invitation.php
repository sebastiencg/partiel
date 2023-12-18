<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: InvitationRepository::class)]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['invitation:read-all'])]

    private ?int $id = null;
    #[Groups(['invitation:read-all'])]
    #[ORM\ManyToOne(inversedBy: 'invitations')]
    private ?Event $privateEvent = null;
    #[Groups(['invitation:read-all'])]
    #[ORM\ManyToOne(inversedBy: 'invitations')]
    private ?Profile $profile = null;
    #[Groups(['invitation:read-all'])]
    #[ORM\Column(length: 255)]
    private ?string $statut = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrivateEvent(): ?Event
    {
        return $this->privateEvent;
    }

    public function setPrivateEvent(?Event $privateEvent): static
    {
        $this->privateEvent = $privateEvent;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }


}
