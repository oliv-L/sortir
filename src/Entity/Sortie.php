<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank (message="Vous devez donner un nom à votre sortie")
     * @Assert\Length (min=2, max=20, minMessage="Votre nom est trop court", maxMessage="Pour plus d'info voir dans les infos sorties")
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @Assert\GreaterThan("today", message="Cette date n'est pas possible, elle est déjà passée")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\NotBlank (message="Quand votre sortie à t'elle lieu?")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @Assert\NotBlank (message="votre sortie dure combien de temps environ?")
     * @ORM\Column(type="integer")
     */
    private $duree;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @Assert\LessThanOrEqual(propertyPath="dateHeureDebut", message="attention, la date est postérieure à l'événement")
     * @Assert\NotBlank (message="la date limite d'inscription est obligatoire.")
     * @ORM\Column(type="date")
     */
    private $dateLimiteInscription;

    /**
     * @Assert\Type("integer", message="merci d'indiquer le nombre de place en chiffre")
     * @Assert\GreaterThan(0, message="combien de places disponibles?")
     * @Assert\NotBlank (message="le nombre de participant max est obligatoire.")
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionsMax;

    /**
     * @Assert\NotBlank (message="merci de laisser une petite description")
     * @ORM\Column(type="text")
     */
    private $infosSortie;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @Assert\NotBlank (message ="choisir une ville et un lieu pour votre évènement")
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="Sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="organisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateurSortie;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="sorties",cascade={"persist"} )
     */
    private $participants;

    /**
     * @Assert\Length (min=5, minMessage="Je ne comprend pas le motif, trop court", max=100, maxMessage="motif trop long, désolé")
     * @ORM\Column(type="text", nullable=true)
     */
    private $motif;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(?\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(?\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getOrganisateurSortie(): ?Participant
    {
        return $this->organisateurSortie;
    }

    public function setOrganisateurSortie(?Participant $organisateurSortie): self
    {
        $this->organisateurSortie = $organisateurSortie;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addSorty($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeSorty($this);
        }

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }
}
