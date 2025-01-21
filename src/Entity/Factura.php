<?php

namespace App\Entity;

use App\Repository\FacturaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: FacturaRepository::class)]
class Factura
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: Types::BIGINT)]
    private ?int $RangoNumeracion;

    #[ORM\Column(length: 255)]
    private ?string $NombreProducto;

    #[ORM\Column(length: 255)]
    private ?string $CantidadProducto;

    #[ORM\Column(length: 255)]
    private ?string $Total;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fechaCreacion = null;


    #[ORM\ManyToOne(inversedBy: 'facturas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clientes $cliente = null;

    public function __construct()
    {
        $this->fechaCreacion = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRangoNumeracion(): ?int
    {
        return $this->RangoNumeracion;
    }

    public function setRangoNumeracion(int $RangoNumeracion): self
    {
        $this->RangoNumeracion = $RangoNumeracion;

        return $this;
    }

    public function getNombreProducto(): ?string
    {
        return $this->NombreProducto;
    }

    public function setNombreProducto(string $NombreProducto): self
    {
        $this->NombreProducto = $NombreProducto;

        return $this;
    }

    public function getCantidadProducto(): ?string
    {
        return $this->CantidadProducto;
    }

    public function setCantidadProducto(string $CantidadProducto): self
    {
        $this->CantidadProducto = $CantidadProducto;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->Total;
    }

    public function setTotal(string $Total): self
    {
        $this->Total = $Total;

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

    public function getCliente(): ?Clientes
    {
        return $this->cliente;
    }

    public function setCliente(?Clientes $cliente): self
    {
        $this->cliente = $cliente;
        return $this;
    }
}
