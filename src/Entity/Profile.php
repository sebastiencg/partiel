<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['profile:read-all','profile:read-one','event:read-one','event:read-all'])]
    private ?int $id = null;

    #[Groups(['profile:read-all','profile:read-one','event:read-one','event:read-all'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;
    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?User $ofUser = null;

    #[Groups(['profile:read-one'])]
    #[ORM\Column(nullable: true)]
    private ?bool $displayName = null;


    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Event::class)]
    private Collection $authorEvent;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'participants')]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'profile', targetEntity: Invitation::class)]
    private Collection $invitations;

    public function __construct()
    {
        $this->authorEvent = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->invitations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOfUser(): ?User
    {
        return $this->ofUser;
    }

    public function setOfUser(?User $ofUser): static
    {
        $this->ofUser = $ofUser;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function isDisplayName(): ?bool
    {
        return $this->displayName;
    }

    public function setDisplayName(?bool $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }





    /**
     * @return Collection<int, Event>
     */
    public function getAuthorEvent(): Collection
    {
        return $this->authorEvent;
    }

    public function addAuthorEvent(Event $authorEvent): static
    {
        if (!$this->authorEvent->contains($authorEvent)) {
            $this->authorEvent->add($authorEvent);
            $authorEvent->setAuthor($this);
        }

        return $this;
    }

    public function removeAuthorEvent(Event $authorEvent): static
    {
        if ($this->authorEvent->removeElement($authorEvent)) {
            // set the owning side to null (unless already changed)
            if ($authorEvent->getAuthor() === $this) {
                $authorEvent->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addParticipant($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): static
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setProfile($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): static
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getProfile() === $this) {
                $invitation->setProfile(null);
            }
        }

        return $this;
    }
}
