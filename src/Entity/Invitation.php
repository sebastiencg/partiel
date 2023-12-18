<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvitationRepository::class)]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invitations')]
    private ?Event $privateEvent = null;

    #[ORM\ManyToOne(inversedBy: 'invitations')]
    private ?Profile $profile = null;

    #[ORM\Column]
    private ?bool $accpetedInvitation = null;

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

    public function isAccpetedInvitation(): ?bool
    {
        return $this->accpetedInvitation;
    }

    public function setAccpetedInvitation(bool $accpetedInvitation): static
    {
        $this->accpetedInvitation = $accpetedInvitation;

        return $this;
    }
}
