<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClassementPilotes
 *
 * @ORM\Table(name="classement_pilotes", indexes={@ORM\Index(name="fk_year", columns={"saisons_year"})})
 * @ORM\Entity(repositoryClass="App\Repository\ClassementPilotesRepository")
 */
class ClassementPilotes
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
     * @ORM\Column(name="pilotes_pilote_id", type="integer", nullable=false)
     */
    private $pilotesPiloteId;

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
    public $saisonsYear;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPilotesPiloteId(): ?int
    {
        return $this->pilotesPiloteId;
    }

    public function setPilotesPiloteId(int $pilotesPiloteId): self
    {
        $this->pilotesPiloteId = $pilotesPiloteId;

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
