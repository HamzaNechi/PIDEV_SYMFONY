<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MembresRepository")
 */
class Membres
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "votre nom est inférieur à {{ limit }} caractéres",
     *      maxMessage = "votre nom est supérieur à {{ limit }} caractéres",
     *      allowEmptyString = false
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $nationalite;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull
     */
    public $date_naissance;

    /**
     * @ORM\ManyToOne(targetEntity=Equipes::class, inversedBy="membres")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $equipe;


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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

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
}
