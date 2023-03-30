# Symfony con BBDD 
***

## Configuración Inicial
***
Lo primero que debemos hacer es instalar el CLI de Symfony, siguiendo las instrucciones de su documentación oficial:

[https://symfony.com/download](https://symfony.com/download)

Una vez instalado el CLI, instalamos un proyecto limpio de Symfony utilizando el comando:

```bash
$ symfony new baseDeDatos
```

Una vez que tenemos instalado nuestro proyecto en su versión básica, vamos a instalar las dependencias que necesitamos:

```bash
$ composer require symfony/maker-bundle --dev
$ composer require symfony/orm-pack
$ composer require symfony/form
$ composer require symfony/debug-pack
$ composer require symfony/twig-pack
$ composer require symfony/webpack-encore-bundle
```

Ahora procedemos a instalar los componentes para tener completamente habilitado nuestro frontend:

```bash
$ npm install
```

> ***Nota:*** Para habilitar completamente los componentes de front (bootstrap) para formularios vamos a hacer lo siguiente:

1. Instalamos bootstrap en nuestro proyecto, ejecutando:

    ```bash
    $ npm install bootstrap --save-dev
    ```

2. En el archivo ***assets/styles/app.css*** agregamos:

    ```css
    @import 'bootstrap';
    ```

3. Luego en el archivo ***config/packages/twig*** agregamos la siguiente línea dentro del bloque **_twig_**

    ```yaml
      twig:
        form_themes: ['bootstrap_5_layout.html.twig']
    ...
    ```

4. Por ultimo, ejecutamos en la consola el comando:

    ```bash
    $ npm run dev
    ```
   
Este momento ya podemos iniciar nuestro servidor local para verificar que el proyecto este correctamente configurado y levantado:

```bash
$ symfony serve
```

> **NOTA:** Para asegurarnos que no existe ningun servidor corriendo, o para dar de baja si alguno esta al aire podemos ejecutar:

```bash
$ symfony server:stop
```

Ahora vamos a generar nuestra BBDD, para eso, vamos a generar nuestro archivo de entorno y lo vamos a llamar ***".env.local"***

```bash
$ touch .env.local
```

Lo abrimos, y copiamos del archivo .env la linea de configuración de la BBDD, comentamos esa linea en el archivo .env, y en nuestro .env.local colocamos las credenciales de la BBDD

```dotenv
DATABASE_URL="mysql://dbUserName:dbPassword@127.0.0.1:3306/dbName?serverVersion=8&charset=utf8mb4"
```

A continuación vamos a crear la BBDD de este proyecto, ejecutando el siguiente comando:

```bash
$ php bin/console doctrine:database:create
```

## Entidades
***

Ahora vamos a crear los archivos desde los cuales manejaremos las migraciones y tablas de la BBDD

Creamos una nueva entidad:

```bash
$ php bin/console make:entity
```

Y el asistente lo completamos con la siguiente informacion:

```bash
 Class name of the entity to create or update (e.g. OrangeChef):
 > Product
Product

 created: src/Entity/Product.php
 created: src/Repository/ProductRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.
 
  New property name (press <return> to stop adding fields):
 > name

 Field type (enter ? to see all types) [string]:
 > string


 Field length [255]:
 > 128

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no
 
   New property name (press <return> to stop adding fields):
 > summary

 Field type (enter ? to see all types) [string]:
 > text

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

```


La siguiente entidad que vamos a crear es ***Metadata***, y tendra la siguiente estructura:

- **id:** PK, int AutoIncrement
- **code:** string 128, not null
- **content:** text, not null

```bash
$ php bin/console make:entity

 Class name of the entity to create or update (e.g. BravePopsicle):
 > Metadata
Metadata

 created: src/Entity/Metadata.php
 created: src/Repository/MetadataRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > code

 Field type (enter ? to see all types) [string]:
 >


 Field length [255]:
 > 128

 Can this field be null in the database (nullable) (yes/no) [no]:
 >

 updated: src/Entity/Metadata.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > content

 Field type (enter ? to see all types) [string]:
 > text
text

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Metadata.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 >


           
  Success! 
           

 Next: When you're ready, create a migration with php bin/console make:migration
```

Ahora vamos a crear la entidad ***Comment*** para que nuestros productos puedan tambien tener comentarios. Esta entidad tendra la siguiente estructura

- **id:** PK, int AutoIncrement
- **content:** text, not null

```bash
$ php bin/console make:entity

 Class name of the entity to create or update (e.g. GrumpyPizza):
 > Comment
Comment

 created: src/Entity/Comment.php
 created: src/Repository/CommentRepository.php

 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > content

 Field type (enter ? to see all types) [string]:
 > text
text

 Can this field be null in the database (nullable) (yes/no) [no]:
 >

 updated: src/Entity/Comment.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > 


           
  Success! 
           

 Next: When you're ready, create a migration with php bin/console make:migration
```

La siguiente entidad es ***Tag*** para que nuestros productos puedan ser identificados por etiquetas, esta entidad tendrá la siguiente estructura:

- **id:** PK, int AutoIncrement
- **name:** string 128, not null

```bash
$ php bin/console make:entity

 Class name of the entity to create or update (e.g. DeliciousPopsicle):
 > Tag
Tag

 created: src/Entity/Tag.php
 created: src/Repository/TagRepository.php

 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > name

 Field type (enter ? to see all types) [string]:
 >


 Field length [255]:
 > 128

 Can this field be null in the database (nullable) (yes/no) [no]:
 >

 updated: src/Entity/Tag.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 >


           
  Success! 
           

 Next: When you're ready, create a migration with php bin/console make:migration
```

Ya tenemos nuestras entidades, ahora vamos a generar las migraciones para que podamos, posteriormente, generar las tablas en nuestra BBDD

Para eso, en primer lugar creamos los archivos de migraciones con el siguiente comando:

```bash
$ php bin/console make:migration
```

Se generará un archivo PHP con el codigo de base de datos para generar estas tablas, de acuerdo al DBMS que tengamos configurado.
Para este ejemplo, el DBMS es MySQL 8.0, por lo que el archivo generado tiene este código:

***/migrations/Version20230320203803.php***
```php
<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320203803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial configuration (four tables)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metadata (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(128) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, summary LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE metadata');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE tag');
    }
}

```

Por último, vamos a ejecutar este archivo de migracion para que se generen las tablas en nuestra BBDD:

```bash
$ php bin/console doctrine:migrations:migrate
```

Si queremos ver el listado de migraciones que tenemos en nuestro sistema, podemos ejecutar el comando

```bash
$ php bin/console doctrine:migrations:list
```

El resultado será algo como esto:

```bash
+------------------------------------------+----------+---------------------+----------------+-------------------------------------+
| Migration Versions                                                                         |                                     |
+------------------------------------------+----------+---------------------+----------------+-------------------------------------+
| Migration                                | Status   | Migrated At         | Execution Time | Description                         |
+------------------------------------------+----------+---------------------+----------------+-------------------------------------+
| DoctrineMigrations\Version20230320203803 | migrated | 2023-03-20 15:49:13 | 0.037s         | Initial configuration (four tables) |
+------------------------------------------+----------+---------------------+----------------+-------------------------------------+
```

Para dar de baja una migracion en especifico, ejecutamos el siguiente comando:

```bash
$ php bin/console doctrine:migrations:execute 'DoctrineMigrations\Version20230320203803' --down
```


## Relaciones de tablas
***

Ya tenemos creadas varias entidades junto con sus repositorios y arhivo de migracion, ahora vamos a generar las relaciones que existen entre esas entidades y sus respectivas actualizaciones en nuestra BBDD.

Para editar una entidad ejecutamos el comando 

```bash
$ php bin/console make:entity
```

El asistente nos pedirá el nombre de la entidad que queremos crear o editar, escribimos Product, y llenamos el resto del asistente como se muestra a continuación:

```bash
Class name of the entity to create or update (e.g. VictoriousChef):
 > Product
Product

 Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > metadata 

 Field type (enter ? to see all types) [string]:
 > relation
relation

 What class should this entity be related to?:
 > Metadata
Metadata

What type of relationship is this?
 ------------ ------------------------------------------------------------------------ 
  Type         Description                                                             
 ------------ ------------------------------------------------------------------------ 
  ManyToOne    Each Product relates to (has) one Metadata.                             
               Each Metadata can relate to (can have) many Product objects.            
                                                                                       
  OneToMany    Each Product can relate to (can have) many Metadata objects.            
               Each Metadata relates to (has) one Product.                             
                                                                                       
  ManyToMany   Each Product can relate to (can have) many Metadata objects.            
               Each Metadata can also relate to (can also have) many Product objects.  
                                                                                       
  OneToOne     Each Product relates to (has) exactly one Metadata.                     
               Each Metadata also relates to (has) exactly one Product.                
 ------------ ------------------------------------------------------------------------ 

 Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
 > OneToOne
OneToOne

 Is the Product.metadata property allowed to be null (nullable)? (yes/no) [yes]:
 > no

 Do you want to add a new property to Metadata so that you can access/update Product objects from it - e.g. $metadata->getProduct()? (yes/no) [no]:
 > no

 updated: src/Entity/Product.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 >


           
  Success! 
           

 Next: When you're ready, create a migration with php bin/console make:migration

```

Ahora vamos a generar la relación de Producto a Comentario, en este caso, un producto puede tener 1 o varios comentarios, por lo que es una relacion 1:n, con lo cual, el asistente deberá ser completado de la siguiente maneta:

```bash
$ php bin/console make:entity

 Class name of the entity to create or update (e.g. AgreeableGnome):
 > Product
Product

 Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > comments

 Field type (enter ? to see all types) [string]:
 > relation
relation

 What class should this entity be related to?:
 > Comment
Comment

What type of relationship is this?
 ------------ ----------------------------------------------------------------------- 
  Type         Description                                                            
 ------------ ----------------------------------------------------------------------- 
  ManyToOne    Each Product relates to (has) one Comment.                             
               Each Comment can relate to (can have) many Product objects.            
                                                                                      
  OneToMany    Each Product can relate to (can have) many Comment objects.            
               Each Comment relates to (has) one Product.                             
                                                                                      
  ManyToMany   Each Product can relate to (can have) many Comment objects.            
               Each Comment can also relate to (can also have) many Product objects.  
                                                                                      
  OneToOne     Each Product relates to (has) exactly one Comment.                     
               Each Comment also relates to (has) exactly one Product.                
 ------------ ----------------------------------------------------------------------- 

 Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
 > OneToMany
OneToMany

 A new property will also be added to the Comment class so that you can access and set the related Product object from it.

 New field name inside Comment [product]:
 >

 Is the Comment.product property allowed to be null (nullable)? (yes/no) [yes]:
 > no

 Do you want to activate orphanRemoval on your relationship?
 A Comment is "orphaned" when it is removed from its related Product.
 e.g. $product->removeComment($comment)

 NOTE: If a Comment may *change* from one Product to another, answer "no".

 Do you want to automatically delete orphaned App\Entity\Comment objects (orphanRemoval)? (yes/no) [no]:
 > yes

 updated: src/Entity/Product.php
 updated: src/Entity/Comment.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 >


           
  Success! 
           

 Next: When you're ready, create a migration with php bin/console make:migration

```


Por último, configuramos la relación m:n (ManyToMany) que existe entre Producto y Tag, llenando el asistente de la siguiente manera:

```bash
$ php bin/console make:entity

 Class name of the entity to create or update (e.g. FierceChef):
 > Product
Product

 Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > tags

 Field type (enter ? to see all types) [string]:
 > relation
relation

 What class should this entity be related to?:
 > Tag
Tag

What type of relationship is this?
 ------------ ------------------------------------------------------------------- 
  Type         Description                                                        
 ------------ ------------------------------------------------------------------- 
  ManyToOne    Each Product relates to (has) one Tag.                             
               Each Tag can relate to (can have) many Product objects.            
                                                                                  
  OneToMany    Each Product can relate to (can have) many Tag objects.            
               Each Tag relates to (has) one Product.                             
                                                                                  
  ManyToMany   Each Product can relate to (can have) many Tag objects.            
               Each Tag can also relate to (can also have) many Product objects.  
                                                                                  
  OneToOne     Each Product relates to (has) exactly one Tag.                     
               Each Tag also relates to (has) exactly one Product.                
 ------------ ------------------------------------------------------------------- 

 Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
 > ManyToMany
ManyToMany

 Do you want to add a new property to Tag so that you can access/update Product objects from it - e.g. $tag->getProducts()? (yes/no) [yes]:
 > yes

 A new property will also be added to the Tag class so that you can access the related Product objects from it.

 New field name inside Tag [products]:
 >

 updated: src/Entity/Product.php
 updated: src/Entity/Tag.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 >


           
  Success! 
           

 Next: When you're ready, create a migration with php bin/console make:migration

```

Procedemos a generar el archivo de migración de estas modificaciones que acabamos de hacer:

```bash
$ php bin/console make:migration
```

Y a continuación, ejecutamos esta migracion generada:

```bash
$ php bin/console doctrine:migrations:migrate
```


## Carga de datos falsos
***

Vamos a llenar nuestra BBDD con datos falsos para poder realizar nuestras pruebas.

Para eso, vamos a ejecutar el comando

```bash
$ php bin/console make:fixtures
```

Sin embargo el sistema dará un error ya que nos hace falta la instalación de una dependencia:

```bash
$ composer require orm-fixtures --dev
```

Una vez instalado este componente, deberemos tener un nuevo directorio dentro de ***/src*** con el nombre de ***/src/DataFixtures*** y dentro de este directorio, tendremos un archivo con el siguiente contenido

***/src/DataFixtures/AppFixtures.php***
```php
<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

```

Vamos a importar las clases que vamos a utilizar en este fixture:

```php
use App\Entity\Metadata;
use App\Entity\Product;
```

Y vamos a modificar un poco el contenido del método **"load()"**

```php
public function load(ObjectManager $manager): void
{
  $product = new Product();
  $product->setName('Producto de Prueba');
  $product->setSummary('Resumen de Prueba');

  $metadata = new Metadata();
  $metadata->setCode(rand(100,200));
  $metadata->setContent('Contenido oficial del producto');

  $manager->persist($metadata);

  $product->setMetadata($metadata);

  $manager->persist($product);

  $manager->flush();
}
```

Para ejecutar esta configuración que acabamos de hacer, corremos el comando

```bash
$ php bin/console doctrine:fixtures:load
```

A continuación vamos a realizar los cambios necesarios para agregar etiquetas y comentarios y relacionarlas con el producto

```php
use App\Entity\Comment;
use App\Entity\Tag;


public function load(ObjectManager $manager): void
{
  $product = new Product();
  $product->setName('Producto de Prueba');
  $product->setSummary('Resumen de Prueba');

  $metadata = new Metadata();
  $metadata->setCode(rand(100,200));
  $metadata->setContent('Contenido oficial del producto');

  $manager->persist($metadata);

  $product->setMetadata($metadata);

  $manager->persist($product);

  $tag1 = new Tag();
  $tag1->setName('Etiqueta #1');
  $manager->persist($tag1);

  $tag2 = new Tag();
  $tag2->setName('Etiqueta #2');
  $manager->persist($tag2);

  $product->addTag($tag1);
  $product->addTag($tag2);

  $comment1 = new Comment();
  $comment1->setContent('Comentario #1');
  $manager->persist($comment1);

  $comment2 = new Comment();
  $comment2->setContent('Comentario #2');
  $manager->persist($comment2);

  $product->addComment($comment1);
  $product->addComment($comment2);

  $manager->flush();
}
```



## Fabrica de datos
***

Vamos ahora a generar la funcionalidad para poder tener una fabrica de datos, para poder simular el tener datos reales en nuestra BBDD para poder realizar nuestras pruebas.

Vamos a empezar por ejecutar lo siguiente:

1. Instalamos el componente necesario para poder generar Factories
   ```bash
   $ composer require zenstruck/foundry --dev
   ```
2. Iniciamos con la generación de nuestro Factory ejecutando
   ```bash
   $ php bin/console make:factory
   ```
3. LLenamos el asistente con la siguiente información
   ```bash
   // Note: pass --test if you want to generate factories in your tests/ directory

   // Note: pass --all-fields if you want to generate default values for all fields, not only required fields
   
   Entity, Document or class to create a factory for:
   [0] App\Entity\Comment
   [1] App\Entity\Metadata
   [2] App\Entity\Product
   [3] App\Entity\Tag
   [4] All
   > 4
   4
   
   created: src/Factory/MetadataFactory.php
   created: src/Factory/ProductFactory.php
   created: src/Factory/CommentFactory.php
   created: src/Factory/TagFactory.php
   
   
   Success!
   
   
   Next: Open your new factory and set default values/states.
   Find the documentation at https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
   ```

Este comando generó nuevos archivos dentro del directorio ***"/src/Factory"***

- CommentFactory.php
- MetadataFactory.php
- ProductFactory.php
- TagFactory.php

El archivo ProductFactory.php por ejemplo, se generó con el siguiente código fuente

***/src/Factory/ProductFactory.php***
```php
<?php

namespace App\Factory;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Product>
 *
 * @method        Product|Proxy create(array|callable $attributes = [])
 * @method static Product|Proxy createOne(array $attributes = [])
 * @method static Product|Proxy find(object|array|mixed $criteria)
 * @method static Product|Proxy findOrCreate(array $attributes)
 * @method static Product|Proxy first(string $sortedField = 'id')
 * @method static Product|Proxy last(string $sortedField = 'id')
 * @method static Product|Proxy random(array $attributes = [])
 * @method static Product|Proxy randomOrCreate(array $attributes = [])
 * @method static ProductRepository|RepositoryProxy repository()
 * @method static Product[]|Proxy[] all()
 * @method static Product[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Product[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Product[]|Proxy[] findBy(array $attributes)
 * @method static Product[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Product[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ProductFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'metadata' => MetadataFactory::new(),
            'name' => self::faker()->sentence(),
            'summary' => self::faker()->text(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Product $product): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Product::class;
    }
}

```

Vamos a empezar a utilizar estas fabricas de datos, para lo cual, nuestra clase ***AppFixtures.php*** la vamos a modificar de tal manera que se vea así:

***/src/DataFixtures/AppFixtures.php***
```php
namespace App\DataFixtures;

use App\Factory\ProductFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ProductFactory::createMany(20);
    }
}

```

Ahora vamos a colocar una configuración un poco mas detallada, para tener datos de prueba en nuestra aplicación. Para eso, dejamos el archivo AppFixtures.php de la siguiente manera:


***/src/DataFixtures/AppFixtures.php***
```php
namespace App\DataFixtures;

use App\Factory\CommentFactory;
use App\Factory\ProductFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TagFactory::createMany(5);
        ProductFactory::createMany(20, [
           'comments' => CommentFactory::new()->many(0,10),
           'tags' => TagFactory::randomRange(2,5),
        ]);
    }
}
```

## Frontend
***

Ahora vamos a visualizar y administrar la información de la BBDD desde el navegador.


Creamos nuestro controlador

```bash
$ php bin/console make:controller Page
```
Por último, de momento lo unico que vamos a hacer es cambiar la ruta de nuestro nuevo controlador para que apunte a la raíz del proyecto, con lo que nuestro controlador deberá quedar de la siguiente manera:

```php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('page/home.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
```

## Plantilla Web
***

Ahora que ya contamos con la configuración para trabajar con el front del proyecto, vamos a proceder con la construcción de la plantilla web.

1. Modificamos el método index() de nuestro PageController
   
   ```php
   #[Route('/', name: 'app_home')]
   public function index(): Response
   {
        return $this->render('page/home.html.twig');
   }
   ```
   
2. Vamos a las plantillas y renombramos el archivo ***/templates/page/index.html.twig*** a ***/templates/page/home.html.twig***
3. Dejamos al archivo ***/templates/page/home.html.twig*** con el siguiente código

   ```html
   {% extends 'base.html.twig' %}

   {% block title %}Hello PageController!{% endblock %}
   
   {% block body %}
   <h1>Hello Home !</h1>
   {% endblock %}  
   ```
4. Dejamos al archivo ***/templates/base.html.twig*** con el siguiente codigo:

   ```html
   <!DOCTYPE html>
   <html>
       <head>
           <meta charset="UTF-8">
           <title>{% block title %}Welcome!{% endblock %}</title>
   
           {% block stylesheets %}
               {{ encore_entry_link_tags('app') }}
           {% endblock %}
   
           {% block javascripts %}
               {{ encore_entry_script_tags('app') }}
           {% endblock %}
       </head>
       <body>
           {{ include('common/_menu.html.twig') }}
   
           <div class="container">
               <div class="row">
                   <div class="col-md-9">
                       {% block body %}{% endblock %}
                   </div>
                   <div class="col-md-3">
                       {{ include('common/_aside.html.twig') }}
                   </div>
               </div>
           </div>
   
       </body>
   </html>
   
   ```

5. Creamos el directorio ***/templates/common***
6. Creamos, dentro de common, los archivos 
   - /templates/common/***_aside.html.twig***
   - /templates/common/***_menu.html.twig***
7. En el archivo ***/templates/common/_menu.html.twig*** colocamos el siguiente código

   ```html
   <header class="navbar navbar-dark bg-dark mb-5">
       <div class="container">
           <span class="navbar-brand mb-0 h1">Symfony/Doctrine</span>
   
           <ul class="navbar-nav">
               <li class="nav-item">
                   <a href="{{ path('app_home') }}" class="nav-link text-white">
                       Home
                   </a>
               </li>
   
           </ul>
       </div>
   </header>
   ```

8. En el archivo ***/templates/common/_aside.html.twig*** colocamos el siguiente código

   ```html
   <div class="p-3 mb-3 bg-dark text-white rounded">
       <p>
           Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tincidunt,
           dui sed pulvinar auctor, arcu lorem faucibus massa, sodales luctus nisi
           nisi consectetur velit. Vestibulum eleifend laoreet congue.
       </p>
       <p>
           Aliquam bibendum
           dolor arcu, quis tristique metus consectetur eget. Aliquam ut lacus lectus.
           Fusce molestie faucibus quam, in dapibus risus tincidunt et. Praesent lobortis
           mollis justo eget aliquet. Maecenas pretium elementum molestie.
       </p>
       <p class="mb-0">
           Mauris venenatis
           accumsan libero eu rhoncus. Ut rhoncus, lectus et aliquet pulvinar, est ligula
           aliquam eros, non dapibus risus odio sit amet mi. Aliquam erat volutpat. Donec
           vitae maximus magna, nec aliquet arcu.
       </p>
   </div>
   
   <p class="">
       Cras <strong>scelerisque lobortis velit</strong>, a imperdiet quam facilisis eget.
       Nullam pellentesque felis sit amet purus pharetra luctus.
       Vivamus eleifend commodo dolor, <strong>quis aliquam odio porta non</strong>.
       Quisque at egestas nibh, et dignissim enim. Aenean id eros enim.
   </p>
   
   <a href="#" class="btn btn-warning btn-lg">Comentarios</a>
   ```

## Listado de Registros
***

Ahora vamos a mostrar en el navegador, en nuestro home, el listado de Productos que se encuentran en la BBDD

Para eso, en primer lugar, vamos a dejar nuestro controlador con el siguiente código:

1. Importamos las clases necesarias para poder realizar la consulta de productos
   ```php
   use App\Entity\Product;
   use Doctrine\ORM\EntityManagerInterface;
   ```
2. Modificamos nuestro método ***index()*** de la siguiente manera:
   ```php
   #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('page/home.html.twig', [
            'products' => $entityManager->getRepository(Product::class)->findAll(),
        ]);
    }
   ```
3. Imprimimos los datos enviados desde el controlador en la vista ***/templates/page/home.html.twig***
```html
{% extends 'base.html.twig' %}

{% block title %}Hello PageController!{% endblock %}

{% block body %}
    {% for product in products %}
        <h2>
            <a href="#" class="text-dark text-decoration-none">{{ product.name }}</a>
        </h2>
        <p>{{ product.summary }}</p>
        <p class="text-muted">
            {{ product.comments|length }} Comentarios
            |
            {% for tag in product.tags %}
                <a href="#" class="badge bg-light text-dark text-decoration-none">{{ tag.name }}</a>
            {% endfor %}
        </p>
    {% endfor %}
{% endblock %}

```

Para ejecutar funciones como map(), reduce() o filter() en twig en lugar de los bucles for, primero debemos instalar estas 2 dependencias:

```bash
$ composer require twig/markdown-extra 
$ composer require league/commonmark
```

Una vez instaladas, podemos dejar el código de la siguiente manera:

***/templates/page/home.html.twig***
```html
{% extends 'base.html.twig' %}

{% block title %}Hello PageController!{% endblock %}

{% block body %}
    {% for product in products %}
        <h2>
            <a href="#" class="text-dark text-decoration-none">{{ product.name }}</a>
        </h2>
        <p>{{ product.summary }}</p>
        <p class="text-muted">
            {{ product.comments|length }} Comentarios
            |
           {{ product.tags|map(tag => '<a href="#" class="badge bg-light text-dark text-decoration-none">' ~ tag.name|markdown_to_html ~ '</a>')|join("")|raw }}
        </p>
    {% endfor %}
{% endblock %}

```

## Filtro por etiqueta
***

Vamos a generar la funcionalidad para que el sistema nos muestre los productos que pertenecen a un determinado tag.

1. Vamos a modificar el Controlador agregandole un método que nos permita hacer este filtrado por etiqueta, y obviamente incluyendo la entidad de Tag para utilizarla

   ```php
    use App\Entity\Tag;
   
    #[Route('/tag/{id}', name: 'app_tag')]
    public function tag(Tag $tag): Response
    {
        return $this->render('page/tag.html.twig', [
            'tag' => $tag,
            'products' => $tag->getProducts(),
        ]);
    }
   ```
   
2. Vamos a separar y optimizar un poco el codigo de las vistas, en primer lugar vamos a generar un nuevo archivo dentro del dorectorio "page", y lo vamos a llamar ***_product.html.twig*** y vamos a colocar el siguiente codigo:

   ***/templates/page/_product.html.twig***
   ```html
   <h2>
       <a href="#" class="text-dark text-decoration-none">{{ product.name }}</a>
   </h2>
   <p>{{ product.summary }}</p>
   <p class="text-muted">
       {{ product.comments|length }} Comentarios
       |
       {{ product.tags|map(tag => '<a href="#" class="badge bg-light text-dark text-decoration-none">' ~ tag.name ~ '</a>')|join("")|raw }}
   </p>
   ```

3. Ahora vamos a reducir el código de ***home.html.twig*** quitando el codigo de impresion de productos y en lugar de eso vamos a incluir la vista que acabamos de crear, y vamos a reemplazar el bucle "for" por la función "map()" de la siguiente manera:

   ***/templates/page/home.html.twig***
   ```html
   {% extends 'base.html.twig' %}
   
   {% block title %}Hello PageController!{% endblock %}
   
   {% block body %}
       {{ products|map(product => include('page/_product.html.twig'))|join("")|raw }}
   {% endblock %}
   ```
Ahora ya tenemos la lista de productos de tal manera que la podemos reutilizar

4.  Vamos ahora a agregar el link de los tags en cada uno de ellos en el archivo ***_product.html.twig*** quedando esta linea de la siguiente manera:
   
   ```html
   {{ product.tags|map(tag => '<a href="'~ path('app_tag', {id: tag.id}) ~'" class="badge bg-light text-dark text-decoration-none">' ~ tag.name ~ '</a>')|join("")|raw }}
   ```

5. Ahora vamos a trabajar con la vista de las etiquetas, para lo cual, creamos el archivo ***tag.html.twig*** dentro del directorio "page" y colocamos el siguiente código:
   
   ***/templates/page/tag.html.twig***
   ```html
   {% extends 'base.html.twig' %}
   
   {% block title %}Hello PageController!{% endblock %}
   
   {% block body %}
       <h3 class="mb-4">TAG: {{ tag.name|upper }}</h3>
       <hr>
       {{ products|map(product => include('page/_product.html.twig'))|join("")|raw }}
   {% endblock %}
   
   ```
**NOTA:** A partir de la versión 6.2 de symfony, este código funciona correctamente, sin embargo, si se realiza este ejercicio en una version anterior, se debe instalar el siguiente componente:

```bash
$ composer require sensio/framework-extra-bundle
```

## Detalle de un registro
***

Vamos ahora a generar una vista que muestre el detalle de un producto, para eso hacemos lo siguiente:

1. Agregamos el siguiente método al controlador:

   ```php
   use App\Entity\Product;
   
   #[Route('/product/{id}', name: 'app_product')]
   public function product(Product $product): Response
   {
      return $this->render('page/product.html.twig', [
         'product' => $product,
      ]);
   }
   ```

2. Creamos el archivo ***product.html.twig*** dentro del directorio **page**
3. Colocamos el siguiente código en la vista:

   ***/templates/page/product.html.twig***
   ```html
   {% extends 'base.html.twig' %}

   {% block title %}Hello PageController!{% endblock %}
   
   {% block body %}
   
       <h1 class="mb-4">{{ product.name|upper }}</h1>
       <p><strong>Código: </strong> # {{ product.metadata.code }}</p>
   
       <p>{{ product.summary }}</p>
       <hr>
   
       <p>{{ product.metadata.content }}</p>
   
       {{ include('page/_info.html.twig') }}
   
       <br>
   
       <div class="px-4">
           {% for comment in product.comments %}
               <div class="row mb-4">
                   <h4 class="col-md-2">ID #{{ comment.id }}</h4>
                   <p class="col-md-10">{{ comment.content }}</p>
               </div>
           {% endfor %}
       </div>
   
   {% endblock %}
   
   ```

4. Creamos el archivo ***_info.html.twig*** dentro de **page**
5. Colocamos el siguiente código en el archivo _info

   ***/templates/page/_info.html.twig***
   ```html
   <p class="text-muted">
       {{ product.comments|length }} Comentarios
       |
       {{ product.tags|map(tag => '<a href="'~ path('app_tag', {'id': tag.id}) ~'" class="badge bg-light text-dark text-decoration-none">' ~ tag.name ~ '</a>')|join("")|raw }}
   </p>
   ```

6. Refactorizamos el archivo ***_product.html.twig*** dejándolo de la siguiente manera:
   
   ***/templates/page/_product.html.twig***
   ```html
   <div class="mb-4">
        <h2>
           <a href="{{ path('app_product', {id: product.id}) }}" class="text-dark text-decoration-none">{{ product.name }}</a>
        </h2>
        <p>{{ product.summary }}</p>
   
        {{ include('page/_info.html.twig') }}
   </div>
   ```

7. Vamos a refactorizar un poco, creamos el archivo ***_comment.html.twig*** en el directorio **page** y le colocamos el siguiente codigo:
   
   ***/templates/page/_comment.html.twig***
   ```html
   <div class="row mb-4">
       <h4 class="col-md-2">ID #{{ comment.id }}</h4>
       <p class="col-md-10">{{ comment.content }}</p>
   </div>
   ```
8. Cambiamos el codigo del archivo ***product.html.twig*** de la siguiente manera

   ```html
   {% extends 'base.html.twig' %}
   
   {% block title %}Hello PageController!{% endblock %}
   
   {% block body %}
   
       <h1 class="mb-4">{{ product.name|upper }}</h1>
       <p><strong>Código: </strong> # {{ product.metadata.code }}</p>
   
       <p>{{ product.summary }}</p>
       <hr>
   
       <p>{{ product.metadata.content }}</p>
   
       {{ include('page/_info.html.twig') }}
   
       <br>
   
       <div class="px-4">
           {{ product.comments|map(comment => include('page/_comment.html.twig'))|join("")|raw }}
       </div>
   
   {% endblock %}
   
   ```


## Lista de comentarios
***

Vamos ahora a construir la lista de comentarios

1. Vamos a modificar el controlador agregando un nuevo metodo de la siguiente manera:

   ```php
   use App\Entity\Comment;
   
   #[Route('/comments', name: 'app_comments')]
    public function comments(EntityManagerInterface $entityManager): Response
    {
        return $this->render('page/comments.html.twig', [
            'comments' => $entityManager->getRepository(Comment::class)->findAll(),
        ]);
    }
   ```

2. Una vez hecho esto, podemos colocar el link hacia esta pagina en el boton de comentarios de nuestro sitio, este boton se encuentra en la vista ***_aside.html.twig***

   ***/templates/common/_aside.html.twig***
   ```html
   <a href="{{ path('app_comments') }}" class="btn btn-warning btn-lg">Comentarios</a>
   ```

3. Vamos a crear nuestra vista de comentarios, para eso creamos el archivo ***comments.html.twig*** dentro de **page** y le colocamos el siguiente codigo:

   ***/templates/page/comments.html.twig***
   ```html
   {% extends 'base.html.twig' %}
   
   {% block title %}Hello PageController!{% endblock %}
   
   {% block body %}
   
       <h1 class="mb-4">Comments</h1>
   
       {{ comments|map(comment => include('page/_comment_header.html.twig'))|join("")|raw }}
   
   {% endblock %}
   
   ```

4. Por ultimo, creamos el archivo ***_comment_header.html.twig*** dentro de **page** y le colocamos el siguiente codigo:

   ***/templates/page/_comment_header.html.twig***
   ```html
   <h4>
       <a href="{{ path('app_product', { id: comment.product.id}) }}" class="text-dark text-decoration-none">
           {{ comment.product.name }}
       </a>
   </h4>
   
   {{ include('page/_comment.html.twig') }}
   ```
   
## createQuery()
***

Ahora vamos a generar las consultas personalizadas, para optimizar nuestras consultas a la BBDD. Para eso trabajaremos con los Repositories

Vamos a empezar por el **ProductRepository.php**, vamos a agregarle el siguiente método

***/src/Repository/ProductRepository.php***
```php
public function findLatest(): array
{
     return $this->getEntityManager()
                      ->createQuery('
                            SELECT p 
                            FROM App\Entity\Product p 
                            ORDER BY p.id DESC'
                      )
            ->setMaxResults(10)
            ->getResult();
}
```

Y en nuestro controlador, actualizamos el método index() de la siguiente manera:

***/src/Controller/PageController.php***
```php
#[Route('/', name: 'app_home')]
public function index(EntityManagerInterface $entityManager): Response
{
     return $this->render('page/home.html.twig', [
         'products' => $entityManager->getRepository(Product::class)->findLatest(),
     ]);
}
```

> **NOTA:** En este ejemplo estamos utilizando **<abbr title="Documentum Query Language">DQL</abbr>**, es el parámetro 
que espera el método createQuery(), este lenguaje sirve para hacer consultas a través de la Entidad, en este caso Product

> **NOTA:** EL método getSQL() nos devuelve la consulta SQL generada con nuestro código  


## createQueryBuilder()
***

Vamos a generar ahora consultas personalizadas, pero ahora utilizando el constructor de consultas.  

Para esto, vamos a modificar el código del repositorio de la siguiente manera:

***/src/Repository/ProductRepository.php***
```php
public function findLatest(): array
{
     return $this->createQueryBuilder('p')
                        ->orderBy('p.id', 'DESC')
                        ->setMaxResults(10)
                        ->getQuery()
                        ->getResult();
}
```

## Optimización del listado total
***

Vamos a optimizar las consultas SQL que se estan generando al momento de construir el listado principal, para eso
vamos a realizar modificaciones en el repositorio de producto, dejando el método del ejemplo anterior de la 
siguiente manera:

```php
public function findLatest(): array
    {
        return $this->createQueryBuilder('product')
                        ->addSelect('comments', 'tags')
                        ->leftJoin('product.comments', 'comments')
                        ->leftJoin('product.tags', 'tags')
                        ->orderBy('product.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }
```

## Optimización de filtro por etiquetas y comentarios
***

Vamos ahora a optimizar los metodos de filtro por tags y de lista de comentarios, y para esto vamos a realizar los siguientes pasos>

1. Vamos a cambiar los métodos tag() y comments() del controlador **PageController**

   ***/src/Controller/PageController.php***
   ```php
   #[Route('/tag/{id}', name: 'app_tag')]
   public function tag(Tag $tag, EntityManagerInterface $entityManager): Response
   {
     return $this->render('page/tag.html.twig', [
         'tag' => $tag,
         'products' => $entityManager->getRepository(Product::class)->getAllByTag($tag),
     ]);
   }
   
   
   #[Route('/comments', name: 'app_comments')]
   public function comments(EntityManagerInterface $entityManager): Response
   {
     return $this->render('page/comments.html.twig', [
         'comments' => $entityManager->getRepository(Comment::class)->findAllComments(),
     ]);
   }
   
   ```

2. Ahora vamos a construir los metodos que estamos invocando, en los repositorios correspondientes:

   ***/src/Repository/ProductRepository.php***
   ```php
   public function getAllByTag(Tag $tag): array
    {
        return $this->createQueryBuilder('product')
            ->setParameter('tag', $tag)
            ->andWhere(':tag MEMBER OF product.tags')
            ->addSelect('comments', 'tags')
            ->leftJoin('product.comments', 'comments')
            ->leftJoin('product.tags', 'tags')
            ->orderBy('product.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
   ```

   ***/src/Repository/CommentRepository.php***
   ```php
   public function findAllComments(): array
    {
        return $this->createQueryBuilder('comment')
                    ->addSelect('product')
                    ->leftJoin('comment.product', 'product')
                    ->orderBy('comment.id', 'DESC')
                    ->getQuery()
                    ->getResult();
    }
   ```