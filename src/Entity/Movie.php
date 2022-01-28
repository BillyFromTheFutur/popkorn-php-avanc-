<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;
    // pas de colonne car ne dois pas être stocker dans la BDD
    private $email;
    /**
     * @ORM\Column(type="integer")
     */
    private $votersNumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $name)
    {
        //rien car ne peut pas etre stocké dans la BDD car aucune colonne ne correspond
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = ($this->getScore()*$this->getVotersNumber()+$score)/($this->getVotersNumber()+1);
        $this->setVotersNumber($this->getVotersNumber()+1);
        return $this;
    }

    public function getVotersNumber(): ?int
    {
        return $this->votersNumber;
    }

    public function setVotersNumber(int $votersNumber): self
    {
        $this->votersNumber = $votersNumber;

        return $this;
    }
}