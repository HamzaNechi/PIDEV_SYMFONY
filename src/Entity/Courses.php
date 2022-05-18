<?php

namespace App\Entity;

use App\Repository\CoursesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass=CoursesRepository::class)
 */
class Courses
{
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCourse;

    /**
     * @ORM\ManyToOne(targetEntity=Circuits::class, inversedBy="course")
     */
    private $circuitid;

    /**
     * @ORM\ManyToOne(targetEntity=Saisons::class, inversedBy="courses")
     */
    private $saison;

    
    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Tickets::class, mappedBy="course")
     */
    private $tickets;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="courses")
     */
    private $organisateur;

  

    public function __construct()
    {
        $this->calendars = new ArrayCollection();
        $this->tickets = new ArrayCollection();
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

    public function getDateCourse(): ?\DateTimeInterface
    {
        return $this->dateCourse;
    }

    public function setDateCourse(\DateTimeInterface $dateCourse): self
    {
        $this->dateCourse = $dateCourse;

        return $this;
    }

    public function getCircuitid(): ?Circuits
    {
        return $this->circuitid;
    }

    public function setCircuitid(?Circuits $circuitid): self
    {
        $this->circuitid = $circuitid;

        return $this;
    }

    public function getSaison(): ?Saisons
    {
        return $this->saison;
    }

    public function setSaison(?Saisons $saison): self
    {
        $this->saison = $saison;

        return $this;
    }
    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Tickets>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Tickets $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setCourse($this);
        }

        return $this;
    }

    public function removeTicket(Tickets $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getCourse() === $this) {
                $ticket->setCourse(null);
            }
        }

        return $this;
    }
   


    public function getOrganisateur(): ?User
    {    
        return $this->organisateur;
        
    }

    public function setOrganisateur(?User $organisateur): self
    {
        if($organisateur->isOrgan()){
             $this->organisateur = $organisateur;
                                    }
        return $this;
    }



   
}
