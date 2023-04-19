<?php

namespace App\Entity;

use App\Entity\Deposito;
use App\Entity\Producto;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\StockDepositoRepository;

/**
 * @ORM\Entity(repositoryClass=StockDepositoRepository::class)
 */
class StockDeposito
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Producto::class, inversedBy="stockDeposito")
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     */
    private $producto;

    /**
     * @ORM\ManyToOne(targetEntity=Deposito::class, inversedBy="stockDeposito")
     * @ORM\JoinColumn(name="deposito_id", referencedColumnName="id")
     */
    private $deposito;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    public function __construct() {
        $this->cantidad = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProducto()
    {
        return $this->producto;
    }

    public function setProducto(Producto $producto)
    {
        $this->producto = $producto;
        return $this;
    }

    public function getDeposito()
    {
        return $this->deposito;
    }

    public function setDeposito(Deposito $deposito)
    {
        $this->deposito = $deposito;
        return $this;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function incrementarCantidad($cantidad)
    {
        $this->cantidad += $cantidad;
        return $this;
    }

    public function decrementarCantidad($cantidad)
    {
        $this->cantidad -= $cantidad;
        return $this;
    }
}
