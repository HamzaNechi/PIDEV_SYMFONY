<?php

namespace App\Entity;

use App\Repository\EquipesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EquipesRepository::class)
 */
class Equipes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

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
    public $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\Email(
     *     message = "Vérifier votre email '{{ value }}'.",
     *     checkMX = true
     * )
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Choisir une image")
     * @Assert\File(mimeTypes={ "image/jpeg","image/png"})
     */
    public $logo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    public $voiture;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    public $pays_origine;

    /**
     * @ORM\OneToMany(targetEntity=Membres::class, mappedBy="equipe",cascade={"all"},orphanRemoval=true)
     */
    private $membres;

    public function __construct()
    {
        $this->membres = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getVoiture(): ?string
    {
        return $this->voiture;
    }

    public function setVoiture(string $voiture): self
    {
        $this->voiture = $voiture;

        return $this;
    }

    public function getPaysOrigine(): ?string
    {
        return $this->pays_origine;
    }

    public function setPaysOrigine(string $pays_origine): self
    {
        $this->pays_origine = $pays_origine;

        return $this;
    }

    /**
     * @return Collection<int, Membres>
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(Membres $membre): self
    {
        if (!$this->membres->contains($membre)) {
            $this->membres[] = $membre;
            $membre->setEquipe($this);
        }

        return $this;
    }

    public function removeMembre(Membres $membre): self
    {
        if ($this->membres->removeElement($membre)) {
            // set the owning side to null (unless already changed)
            if ($membre->getEquipe() === $this) {
                $membre->setEquipe(null);
            }
        }

        return $this;
    }
}
