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
     * @ORM\OneToMany(targetEntity=StockDeposito::class, mappedBy="deposito")
     */
    private $stocks;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $pais;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $provincia;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $ciudad;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $calle;

    /**
     * @ORM\Column(type="integer")
     */
    private $altura;

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
            "codigo" => $this->getCodigo(),
            "nombre" => $this->getNombre(),
            "pais" => $this->getPais(),
            "provincia" => $this->getProvincia(),
            "ciudad" => $this->getCiudad(),
            "calle" => $this->getCalle(),
            "altura" => $this->getAltura()
        ];
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(string $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(string $ciudad): self
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getCalle(): ?string
    {
        return $this->calle;
    }

    public function setCalle(string $calle): self
    {
        $this->calle = $calle;

        return $this;
    }

    public function getAltura(): ?int
    {
        return $this->altura;
    }

    public function setAltura(int $altura): self
    {
        $this->altura = $altura;

        return $this;
    }
}
