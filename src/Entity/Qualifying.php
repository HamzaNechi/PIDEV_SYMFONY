<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\QualifyingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=QualifyingRepository::class)
 */
class Qualifying
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex(
     * pattern="/^([0-5][0-9])(:[0-5][0-9](?:[.]\d{1,3})?)?$/",
     * message="Insert valid lap time (mm:ss.xxx)"
     * )
     */
    private $q1;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex(
     * pattern="/^([0-5][0-9])(:[0-5][0-9](?:[.]\d{1,3})?)?$/",
     * message="Insert valid lap time (mm:ss.xxx)"
     * ) 
     */
    private $q2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex(
     * pattern="/^([0-5][0-9])(:[0-5][0-9](?:[.]\d{1,3})?)?$/",
     * message="Insert valid lap time (mm:ss.xxx) "
     * )
     */
    private $q3;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity=Pilotes::class, inversedBy="qualifyings")
     * @ORM\JoinColumn(nullable=false)
     *  @Assert\NotBlank
     */
    private $pilote;

   

    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQ1(): ?string
    {
        return $this->q1;
    }

    public function setQ1(string $q1): self
    {
        $this->q1 = $q1;

        return $this;
    }

    public function getQ2(): ?string
    {
        return $this->q2;
    }

    public function setQ2(string $q2): self
    {
        $this->q2 = $q2;

        return $this;
    }

    public function getQ3(): ?string
    {
        return $this->q3;
    }

    public function setQ3(string $q3): self
    {
        $this->q3 = $q3;

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

    public function getPilote(): ?Pilotes
    {
        return $this->pilote;
    }

    public function setPilote(?Pilotes $pilote): self
    {
        $this->pilote = $pilote;

        return $this;
    }

   
    
}
