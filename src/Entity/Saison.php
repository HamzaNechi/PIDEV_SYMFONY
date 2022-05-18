<?php

namespace App\Entity;

use App\Repository\SaisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SaisonRepository::class)
 */
class Saison
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Year;



    /**
     * @ORM\OneToMany(targetEntity=Courses::class, mappedBy="saison")
     */
    private $courses;

    public function __construct()
    {
        $this->saisons = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->Year;
    }

    public function setYear(int $Year): self
    {
        $this->Year = $Year;

        return $this;
    }

    /**
     * @return Collection<int, courses>
     */
    public function getSaisons(): Collection
    {
        return $this->saisons;
    }

    public function addSaison(courses $saison): self
    {
        if (!$this->saisons->contains($saison)) {
            $this->saisons[] = $saison;
            $saison->setSaison($this);
        }

        return $this;
    }

    public function removeSaison(courses $saison): self
    {
        if ($this->saisons->removeElement($saison)) {
            // set the owning side to null (unless already changed)
            if ($saison->getSaison() === $this) {
                $saison->setSaison(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Courses>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Courses $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setSaison($this);
        }

        return $this;
    }

    public function removeCourse(Courses $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getSaison() === $this) {
                $course->setSaison(null);
            }
        }

        return $this;
    }
}
