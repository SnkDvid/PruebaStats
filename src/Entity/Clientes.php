<?php

namespace App\Entity;

use App\Repository\ClientesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ClientesRepository::class)]
class Clientes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $Nombre;

    #[ORM\Column(length: 255)]
    private ?string $Direccion;

    #[ORM\Column(length: 30)]
    private ?string $Correo;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $Celular;

    #[ORM\Column(length: 30)]
    private ?string $Civil;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $Estado = True;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaCreacion = null;

    #[ORM\OneToMany(mappedBy: 'cliente', targetEntity: Factura::class)]
    private Collection $facturas;

    public function __construct()
    {
        $this->facturas = new ArrayCollection();
        $this->fechaCreacion = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): static
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->Direccion;
    }

    public function setDireccion(string $Direccion): static
    {
        $this->Direccion = $Direccion;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->Correo;
    }

    public function setCorreo(string $Correo): static
    {
        $this->Correo = $Correo;

        return $this;
    }

    public function getCelular(): ?string
    {
        return $this->Celular;
    }

    public function setCelular(string $Celular): static
    {
        $this->Celular = $Celular;

        return $this;
    }

    public function getCivil(): ?string
    {
        return $this->Civil;
    }

    public function setCivil(string $Civil): static
    {
        $this->Civil = $Civil;

        return $this;
    }

    public function getEstado(): ?bool
    {
        return $this->Estado;
    }

    public function setEstado(bool $Estado): static
    {
        $this->Estado = $Estado;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;
        return $this;
    }

    /**
     * @return Collection<int, Factura>
     */
    public function getFacturas(): Collection
    {
        return $this->facturas;
    }

    public function addFactura(Factura $factura): self
    {
        if (!$this->facturas->contains($factura)) {
            $this->facturas->add($factura);
            $factura->setCliente($this);
        }

        return $this;
    }

    public function removeFactura(Factura $factura): self
    {
        if ($this->facturas->removeElement($factura)) {
            // set the owning side to null (unless already changed)
            if ($factura->getCliente() === $this) {
                $factura->setCliente(null);
            }
        }

        return $this;
    }
}
