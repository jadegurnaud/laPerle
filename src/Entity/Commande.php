<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Livraison $livraison_id = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client_id = null;

    #[ORM\OneToOne(mappedBy: 'commande_id', cascade: ['persist', 'remove'])]
    private ?Paiement $paiement = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();

        $this->livraison_id = new Livraison();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getLivraison_Id(): ?Livraison
    {
        return $this->livraison_id;
    }

    public function getLivraisonId(): ?Livraison
    {
        return $this->livraison_id;
    }

    public function setLivraisonId(Livraison $livraison_id): static
    {
        $this->livraison_id = $livraison_id;

        return $this;
    }

    public function getClient_Id(): ?Client
    {
        return $this->client_id;
    }
    public function getClientId(): ?Client
    {
        return $this->client_id;
    }


    public function setClientId(?Client $client_id): static
    {
        $this->client_id = $client_id;

        return $this;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(Paiement $paiement): static
    {
        // set the owning side of the relation if necessary
        if ($paiement->getCommandeId() !== $this) {
            $paiement->setCommandeId($this);
        }

        $this->paiement = $paiement;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCommande($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCommande() === $this) {
                $produit->setCommande(null);
            }
        }

        return $this;
    }
}
