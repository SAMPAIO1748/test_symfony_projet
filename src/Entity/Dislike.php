<?php

namespace App\Entity;

use App\Repository\DislikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DislikeRepository::class)
 */
class Dislike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Chambre::class, inversedBy="dislikes")
     */
    private $chambre;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="dislikes")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): self
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
