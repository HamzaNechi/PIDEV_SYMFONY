<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClassementEquipes
 *
 * @ORM\Table(name="classement_equipes", indexes={@ORM\Index(name="fk_year2", columns={"saisons_year"})})
 * @ORM\Entity(repositoryClass="App\Repository\ClassementEquipesRepository")
 */
class ClassementEquipes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="equipes_equipe_id", type="integer", nullable=false)
     */
    private $equipesEquipeId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="points_total", type="integer", nullable=true)
     */
    private $pointsTotal;

    /**
     * @var int|null
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var \Saisons
     *
     * @ORM\ManyToOne(targetEntity="Saisons")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="saisons_year", referencedColumnName="id")
     * })
     */
    private $saisonsYear;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipesEquipeId(): ?int
    {
        return $this->equipesEquipeId;
    }

    public function setEquipesEquipeId(int $equipesEquipeId): self
    {
        $this->equipesEquipeId = $equipesEquipeId;

        return $this;
    }

    public function getPointsTotal(): ?int
    {
        return $this->pointsTotal;
    }

    public function setPointsTotal(?int $pointsTotal): self
    {
        $this->pointsTotal = $pointsTotal;

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

    public function getSaisonsYear(): ?Saisons
    {
        return $this->saisonsYear;
    }

    public function setSaisonsYear(?Saisons $saisonsYear): self
    {
        $this->saisonsYear = $saisonsYear;

        return $this;
    }


}
