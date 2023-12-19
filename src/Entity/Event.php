<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:read-one','event:read-all','invitation:read-all','contribution:read-all'])]
    private ?int $id = null;

    #[Groups(['event:read-one','event:read-all','invitation:read-all','contribution:read-all'])]
    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[Groups(['event:read-one','event:read-all','invitation:read-all','contribution:read-all'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Groups(['event:read-one','event:read-one'])]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateInit = null;

    #[Groups(['event:read-one','event:read-all'])]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $DateEnd = null;



    #[Groups(['event:read-one','event:read-all'])]
    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[Groups(['event:read-one','event:read-all'])]
    #[ORM\Column(length: 255)]
    private ?string $placeType = null;
    #[Groups(['event:read-one','event:read-all','invitation:read-all','contribution:read-all'])]
    #[ORM\ManyToOne(inversedBy: 'authorEvent')]
    private ?Profile $author = null;
    #[Groups(['event:read-one'])]
    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'events')]
    private Collection $participants;

    #[ORM\OneToMany(mappedBy: 'privateEvent', targetEntity: Invitation::class)]
    private Collection $invitations;

    #[ORM\Column(nullable: true)]
    private ?bool $cancel = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Contributions::class)]
    private Collection $contributions;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->contributions = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateInit(): ?\DateTimeImmutable
    {
        return $this->dateInit;
    }

    public function setDateInit(\DateTimeImmutable $dateInit): static
    {
        $this->dateInit = $dateInit;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeImmutable
    {
        return $this->DateEnd;
    }

    public function setDateEnd(\DateTimeImmutable $DateEnd): static
    {
        $this->DateEnd = $DateEnd;

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

    public function getPlaceType(): ?string
    {
        return $this->placeType;
    }

    public function setPlaceType(string $placeType): static
    {
        $this->placeType = $placeType;

        return $this;
    }

    public function getAuthor(): ?Profile
    {
        return $this->author;
    }

    public function setAuthor(?Profile $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Profile $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(Profile $participant): static
    {
        $this->participants->removeElement($participant);

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
            $invitation->setPrivateEvent($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): static
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getPrivateEvent() === $this) {
                $invitation->setPrivateEvent(null);
            }
        }

        return $this;
    }

    public function isCancel(): ?bool
    {
        return $this->cancel;
    }

    public function setCancel(?bool $cancel): static
    {
        $this->cancel = $cancel;

        return $this;
    }

    /**
     * @return Collection<int, Contributions>
     */
    public function getContributions(): Collection
    {
        return $this->contributions;
    }

    public function addContribution(Contributions $contribution): static
    {
        if (!$this->contributions->contains($contribution)) {
            $this->contributions->add($contribution);
            $contribution->setEvent($this);
        }

        return $this;
    }

    public function removeContribution(Contributions $contribution): static
    {
        if ($this->contributions->removeElement($contribution)) {
            // set the owning side to null (unless already changed)
            if ($contribution->getEvent() === $this) {
                $contribution->setEvent(null);
            }
        }

        return $this;
    }

}
