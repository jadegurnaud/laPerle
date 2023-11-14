<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $depart = null;

    #[ORM\Column(length: 255)]
    private ?string $arrive = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function __construct()
    {
        $this->status = 'en attente';
        $currentDateTime = new DateTime('now');
        $this->depart = $currentDateTime->format('Y-m-d H:i:s') ;
        $this->arrive = $currentDateTime->format('Y-m-d H:i:s') ;
    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(?string $depart): static
    {
        $this->depart = $depart;

        return $this;
    }

    public function getArrive(): ?string
    {
        return $this->arrive;
    }

    public function setArrive(?string $arrive): static
    {
        $this->arrive = $arrive;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
