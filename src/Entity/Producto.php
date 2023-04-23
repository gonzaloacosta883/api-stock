<?php

namespace App\Entity;

use App\Entity\Categoria;
use App\Entity\StockDeposito;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ProductoRepository::class)
 */
class Producto
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codigoColor;

    /**
     * USD
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="productos")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @ORM\OneToMany(targetEntity=StockDeposito::class, mappedBy="producto")
     */
    private $stocks;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $marca;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $directorio;

    /**
     * @ORM\OneToMany(targetEntity=ArchivoAdjunto::class, mappedBy="producto", orphanRemoval=true)
     */
    private $archivosAdjuntos;

    public function __construct() {
        $this->stocks = new ArrayCollection();
        $this->archivosAdjuntos = new ArrayCollection();
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

    public function getCodigoColor()
    {
        return $this->codigoColor;
    }

    public function setCodigoColor($codigoColor)
    {
        $this->codigoColor = $codigoColor;
        return $this;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
        return $this;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function setCategoria(Categoria $categoria)
    {
        $this->categoria = $categoria;
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
            "codigoColor" => $this->getCodigoColor(),
            "precio" => $this->getPrecio(),
            "marca" => $this->getMarca(),
            "directorio" => $this->getDirectorio()
        ];
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(?string $marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    public function getDirectorio(): ?string
    {
        return $this->directorio;
    }

    public function setDirectorio(?string $directorio): self
    {
        $this->directorio = $directorio;

        return $this;
    }

    /**
     * @return Collection<int, ArchivoAdjunto>
     */
    public function getArchivosAdjuntos(): Collection
    {
        return $this->archivosAdjuntos;
    }

    public function addArchivosAdjunto(ArchivoAdjunto $archivosAdjunto): self
    {
        if (!$this->archivosAdjuntos->contains($archivosAdjunto)) {
            $this->archivosAdjuntos[] = $archivosAdjunto;
            $archivosAdjunto->setProducto($this);
        }

        return $this;
    }

    public function removeArchivosAdjunto(ArchivoAdjunto $archivosAdjunto): self
    {
        if ($this->archivosAdjuntos->removeElement($archivosAdjunto)) {
            // set the owning side to null (unless already changed)
            if ($archivosAdjunto->getProducto() === $this) {
                $archivosAdjunto->setProducto(null);
            }
        }

        return $this;
    }
}
