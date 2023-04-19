<?php

namespace App\Entity;

use App\Entity\StockDeposito;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use App\Repository\DepositoRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=DepositoRepository::class)
 */
class Deposito
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\OneToMany(targetEntity=StockDeposito::class, mappedBy="deposito")
     */
    private $stocks;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigo;

    public function __construct() {
        $this->stocks = new ArrayCollection();
    }

    public function addStock(StockDeposito $stock) {
        $this->stocks[] = $stock;
    }

    public function getStocks() {
        return $this->stocks;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function __toArray()
    {
        return [
            "id" => $this->getId(),
            "codigo" => $this->getCodigo(),
            "nombre" => $this->getNombre(),
            "direccion" => $this->getDireccion()
        ];
    }
}
