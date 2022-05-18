<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ParticipationRepository::class)
 */
class Participation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     
     * @ORM\ManyToOne(targetEntity=Pilotes::class, inversedBy="participations")
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $pilote;

    /**
    
     * @ORM\ManyToOne(targetEntity=Equipes::class, inversedBy="participations")
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    private $equipe;

    /** 
     * @ORM\ManyToOne(targetEntity=Courses::class, inversedBy="participations")
     *  @Assert\NotBlank
     * @Assert\NotNull
     */
    private $course;

   

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("post:read")
     * 
     */
    private $grid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("post:read")
     * 
     */
    private $position;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("post:read")
     * 
     */
    private $points;

    /**
     * @ORM\OneToOne(targetEntity=Qualifying::class,orphanRemoval=true)
     * @ORM\JoinColumn(name="qualifying_id", referencedColumnName="id")
     */
    private $qualifying;


   


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPilote(): ?Pilotes
    {
        return $this->pilote;
    }

    public function setPilote(?Pilotes $pilote): self
    {
        $this->pilote = $pilote;

        return $this;
    }

    public function getEquipe(): ?Equipes
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipes $equipe): self
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getCourse(): ?Courses
    {
        return $this->course;
    }

    public function setCourse(?Courses $course): self
    {
        $this->course = $course;

        return $this;
    }

   

    public function getGrid(): ?int
    {
        return $this->grid;
    }

    public function setGrid(?int $grid): self
    {
        $this->grid = $grid;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getQualifying(): ?Qualifying
    {
        return $this->qualifying;
    }

    public function setQualifying(?Qualifying $qualifying): self
    {
        $this->qualifying = $qualifying;

        return $this;
    }
}
