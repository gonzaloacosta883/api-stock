# API-STOCK <img src="./assets/images/box.svg" height=50 />
 Aplicación web para la gestión de alquileres de películas en un videoclub, aquellos negocios donde uno alquilaba los VHS o DVDs para verlos en sus respectivos reproductores. Previo a la creación y expansión de las plataformas de streaming.

## Software requerido (Windows)
<a href="https://github.com/symfony-cli/symfony-cli/releases/download/v5.5.6/symfony-cli_windows_amd64.zip"><img src="https://img.shields.io/badge/Symfony-000000?style=for-the-badge&logo=Symfony&logoColor=white"/></a>
<a href="https://dev.mysql.com/get/Downloads/MySQL-8.0/mysql-8.0.33-winx64.zip">
<img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white"/>
</a>
<a href="https://nodejs.org/dist/v18.16.1/node-v18.16.1-x64.msi">
<img src="https://img.shields.io/badge/Node.js-43853D?style=for-the-badge&logo=node.js&logoColor=white">
</a>
<a href="https://windows.php.net/downloads/releases/archives/php-7.4.32-Win32-vc15-x64.zip">
<img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white">
</a>
<a href="https://getcomposer.org/Composer-Setup.exe">
<img src="https://getcomposer.org/img/logo-composer-transparent2.png" height=30></a>


## Configuración de desarrollo
Primero clone el repositorio y ubiquese en el directorio del proyecto.

```bash
git clone https://github.com/gonzaloacosta883/api-stock.git
cd api-stock
```

### Configuración local (Linux & Windows)
Instalar las siguientes dependencias:
```bash
cd api-stock
composer install
yarn install
yarn encore dev
```
Si desea modificar el motor de bbdd a utilizar puede hacerlo modificando la siguiente variable de entorno en el archivo .env del directorio del proyecto.
```bash
DATABASE_URL=mysql://[user]:[Password]@[Host]:[PuertoMySql]/[NombreBBDD]
```
Ejecutar los siguiente comandos:
```bash
cd videclub
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
php bin/console doctrine:fixture:load
```

#### Levantar Servidor Symfony
```bash
symfony server:start -d --port=8000
symfony open:local
symfony server:stop
```

#### Documentación Importante
[hashing-passwords](https://symfony.com/doc/current/security.html#registering-the-user-hashing-passwords)