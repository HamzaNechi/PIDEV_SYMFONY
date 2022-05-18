<?php

namespace App\Entity;

use App\Repository\CircuitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CircuitsRepository::class)
 */
class Circuits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $pays;



    /**
     * @ORM\Column(type="integer")
     */
    private $longeur;

    /**
     * @ORM\Column(type="integer")
     */
    private $course_distance;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $capacite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;
    /**
     * @ORM\OneToMany(targetEntity=Courses::class, mappedBy="circuitid")
     */
    private $course;

    public function __construct()
    {
        $this->course = new ArrayCollection();
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

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getLongeur(): ?int
    {
        return $this->longeur;
    }

    public function setLongeur(int $longeur): self
    {
        $this->longeur = $longeur;

        return $this;
    }

    public function getCourseDistance(): ?int
    {
        return $this->course_distance;
    }

    public function setCourseDistance(int $course_distance): self
    {
        $this->course_distance = $course_distance;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    // public function getCourses(): ?Courses
    // {
    //     return $this->courses;
    // }

    // public function setCourses(?Courses $courses): self
    // {
    //     $this->courses = $courses;

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, Courses>
    //  */
    // public function getCourse(): Collection
    // {
    //     return $this->course;
    // }

    // public function addCourse(Courses $course): self
    // {
    //     if (!$this->course->contains($course)) {
    //         $this->course[] = $course;
    //         $course->setCircuitid($this);
    //     }

    //     return $this;
    // }

    // public function removeCourse(Courses $course): self
    // {
    //     if ($this->course->removeElement($course)) {
    //         // set the owning side to null (unless already changed)
    //         if ($course->getCircuitid() === $this) {
    //             $course->setCircuitid(null);
    //         }
    //     }

    //     return $this;
    // }
}
