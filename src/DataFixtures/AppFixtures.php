<?php

namespace App\DataFixtures;

use App\Entity\Deposito;
use App\Entity\Producto;
use App\Entity\Categoria;
use App\Entity\StockDeposito;
use App\Repository\DepositoRepository;
use App\Repository\ProductoRepository;
use App\Repository\CategoriaRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    private $productoRepository;
    private $depositoRepository;
    private $categoriaRepository;
    private $params;

    public function __construct(
        ParameterBagInterface $params, 
        ProductoRepository $productoRepository,
        DepositoRepository $depositoRepository,
        CategoriaRepository $categorieRepository
    )
    {
        $this->productoRepository = $productoRepository;
        $this->depositoRepository = $depositoRepository;
        $this->categorieRepository = $categorieRepository;
        $this->params = $params;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadDepositos($manager);
        $manager->flush();
        $this->loadCategories($manager);
        $manager->flush();
        $this->loadProcessorsAMD($manager);
        $manager->flush();
        $this->loadMothersAMD($manager);
        $manager->flush();
        $this->loadStocks($manager);
        $manager->flush();
    }

    public function loadDepositos(ObjectManager $manager)
    {
        $codigo = $this->depositoRepository->getLastCodigo()[1];
        if($codigo == NULL)
            $codigo = 0;
        
        $deposito = new Deposito();
        $deposito->setNombre("A1");
        $deposito->setPais("Argentina");
        $deposito->setProvincia("Entre Rios");
        $deposito->setCiudad("Parana");
        $deposito->setCalle("Almafuerte");
        $deposito->setAltura(3000);
        $deposito->setCodigo($codigo++);
        $manager->persist($deposito);

        $deposito2 = new Deposito();
        $deposito2->setNombre("B1");
        $deposito2->setPais("Argentina");
        $deposito2->setProvincia("Entre Rios");
        $deposito2->setCiudad("Parana");
        $deposito2->setCalle("Almte. Guillermo Brown");
        $deposito2->setAltura(1182);
        $deposito2->setCodigo($codigo++);
        $manager->persist($deposito2);
    }

    private function loadCategories(ObjectManager $manager)
    {
        $codigo = $this->categorieRepository->getLastCodigo()[1];
        if($codigo == NULL)
            $codigo = 1;

        $categories = [
            [
                "codigo" => $codigo++,
                "nombre" => "Notebooks"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Procesadores"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Mothers"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Placas de Video"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Memorias RAM"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Almacenamiento"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Refrigeracion"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Gabinetes"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Fuentes"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Monitores y Televisores"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Periféricos"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Sillas Gamers"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Conectividad"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Estabilizadores y UPS"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Cables y Adaptadores"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Celulares y Smartwatch"
            ],
            [
                "codigo" => $codigo++,
                "nombre" => "Tablets"
            ]
        ];

        for ($i=0; $i < count($categories) ; $i++) { 
            $categorie = new Categoria();
            $categorie->setCodigo($categories[$i]["codigo"]);
            $categorie->setNombre($categories[$i]["nombre"]);

            $manager->persist($categorie);
        }
    }

    private function loadProcessorsAMD(ObjectManager $manager)
    {
        $processorsCategory = $manager->getRepository(Categoria::class)
            ->findOneBy(["codigo" => 2]);
        $codigo = $this->productoRepository->getLastCodigo()[1];
        if($codigo == NULL)
            $codigo = 1;

        $processorsAMD = [
            [
                "nombre" => "RYZEN 3 3200G",
                "codigoColor" => null,
                "precio" => 66150,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 3 4100 SIN COOLER",
                "codigoColor" => null,
                "precio" => 35750,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 3 4100",
                "codigoColor" => null,
                "precio" => 45050,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 5 4500",
                "codigoColor" => null,
                "precio" => 60400,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 5 3600",
                "codigoColor" => null,
                "precio" => 79190,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 5 4650G PRO",
                "codigoColor" => null,
                "precio" => 98200,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 5 5600G",
                "codigoColor" => null,
                "precio" => 99100,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 5 5500",
                "codigoColor" => null,
                "precio" => 105000,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 5 5600",
                "codigoColor" => null,
                "precio" => 111800,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 7 4750G PRO",
                "codigoColor" => null,
                "precio" => 112000,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 5 7600",
                "codigoColor" => null,
                "precio" => 121950,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 5 7600X SIN COOLER",
                "codigoColor" => null,
                "precio" => 130050,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 5 5600X",
                "codigoColor" => null,
                "precio" => 141750,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 7 5700G",
                "codigoColor" => null,
                "precio" => 147000,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 7 7700",
                "codigoColor" => null,
                "precio" => 185300,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 7 5700X SIN COOLER",
                "codigoColor" => null,
                "precio" => 203200,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 7 5800X SIN COOLER",
                "codigoColor" => null,
                "precio" => 205800,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 9 7900",
                "codigoColor" => null,
                "precio" => 258200,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 9 7900X SIN COOLER",
                "codigoColor" => null,
                "precio" => 281300,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 9 5950X SIN COOLER",
                "codigoColor" => null,
                "precio" => 309750,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ],
            [
                "nombre" => "RYZEN 9 7950X SIN COOLER",
                "codigoColor" => null,
                "precio" => 355400,
                "categoria" => $processorsCategory,
                "codigo" => $codigo++,
                "marca" => "AMD"
            ], 
        ];

        $depositoA1 = $this->depositoRepository->findOneBy(["nombre" => "A1"]);
        $depositoB1 = $this->depositoRepository->findOneBy(["nombre" => "B1"]);

        for ($i=0; $i < count($processorsAMD) ; $i++) { 
            
            $nombreFormateado = str_replace('/',"-",$processorsAMD[$i]["nombre"]);
            $nombreFormateado = str_replace(' ',"-",$nombreFormateado);

            $processor = new Producto();
            $processor->setNombre($nombreFormateado);
            $processor->setPrecio($processorsAMD[$i]["precio"]);
            $processor->setCategoria($processorsAMD[$i]["categoria"]);
            $processor->setCodigo($processorsAMD[$i]["codigo"]);
            $processor->setMarca($processorsAMD[$i]["marca"]);

            $path = $this->params->get('archivos_adjuntos_processors_directory') . "/" . $nombreFormateado;
            if (str_contains(php_uname("s"), "Windows") == true) {
                $path = str_replace("/", "'\'", $path);
            }
            $path = str_replace("'", "", $path);

            $processor->setDirectorio($path);
            $manager->persist($processor);
        }
    }

    private function loadMothersAMD(ObjectManager $manager)
    {
        $mothersCategory = $manager->getRepository(Categoria::class)
            ->findOneBy(["codigo" => 3]);
        $codigo = $this->productoRepository->getLastCodigo()[1];
        if($codigo == NULL)
            $codigo = 1;

        $mothersAMD = [
            [
                "nombre" => "ASUS PRO A320M-R WI-FI/CSM AM4 OEM",
                "codigoColor" => null,
                "precio" => 29900,
                "categoria" => $mothersCategory,
                "codigo" => $codigo++,
                "marca" => "ASUS"
            ],
            [
                "nombre" => "MSI A320M-A PRO AM4",
                "codigoColor" => null,
                "precio" => 31500,
                "categoria" => $mothersCategory,
                "codigo" => $codigo++,
                "marca" => "MSI"
            ],
            [
                "nombre" => "ASUS PRIME A320M-K AM4",
                "codigoColor" => null,
                "precio" => 33050,
                "categoria" => $mothersCategory,
                "codigo" => $codigo++,
                "marca" => "ASUS"
            ],
            [
                "nombre" => "MSI A520M-A PRO AM4",
                "codigoColor" => null,
                "precio" => 37500,
                "categoria" => $mothersCategory,
                "codigo" => $codigo++,
                "marca" => "MSI"
            ],
            [
                "nombre" => "ASROCK-A520M-HDV-AM4",
                "codigoColor" => null,
                "precio" => 37600,
                "categoria" => $mothersCategory,
                "codigo" => $codigo++,
                "marca" => "ASROCK"
            ],
        ];

        for ($i=0; $i < count($mothersAMD) ; $i++) { 
            
            $nombreFormateado = str_replace('/',"-",$mothersAMD[$i]["nombre"]);
            $nombreFormateado = str_replace(' ',"-",$nombreFormateado);

            $mother = new Producto();
            $mother->setNombre($nombreFormateado);
            $mother->setPrecio($mothersAMD[$i]["precio"]);
            $mother->setCategoria($mothersAMD[$i]["categoria"]);
            $mother->setCodigo($mothersAMD[$i]["codigo"]);
            $mother->setMarca($mothersAMD[$i]["marca"]);

            $path = $this->params->get('archivos_adjuntos_motherboards_directory') . "/" . $nombreFormateado;
            if (str_contains(php_uname("s"), "Windows") == true) {
                $path = str_replace("/", "'\'", $path);
            }
            $path = str_replace("'", "", $path);

            $mother->setDirectorio($path);
            $manager->persist($mother);
        }
    }

    public function loadStocks(ObjectManager $manager)
    {
        $productos = $this->productoRepository->findAll();
        $depositos = $this->depositoRepository->findAll();

        foreach ($depositos as $deposito) {
            foreach ($productos as $producto) {
                $stock = new StockDeposito();
                $stock->setProducto($producto);
                $stock->setDeposito($deposito);
                $stock->incrementarCantidad(rand(15, 250));
                $manager->persist($stock);
            }
        }
    }
}
