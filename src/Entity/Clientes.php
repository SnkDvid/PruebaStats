<?php

namespace App\Entity;

use App\Repository\ClientesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientesRepository::class)]
class Clientes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $Nombre;

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

    public function __construct()
    {
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
}
