<?php

namespace App\Entity;

use App\Repository\ReabasteceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReabasteceRepository::class)]
class Reabastece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Productos::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Productos $producto = null;

    #[ORM\Column]
    private ?int $unidades = null;

    public function getid(): ?int
    {
        return $this->id;
    }

    public function getProducto(): ?Productos
    {
        return $this->producto;
    }

    public function setProducto(?Productos $producto): static
    {
        $this->producto = $producto;

        return $this;
    }

    public function getUnidades(): ?int
    {
        return $this->unidades;
    }

    public function setUnidades(int $unidades): static
    {
        $this->unidades = $unidades;

        return $this;
    }
}