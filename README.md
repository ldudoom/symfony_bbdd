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
$ composer require symfony/debug-bundle
$ composer require symfony/twig-pack
$ composer require symfony/webpack-encore-bundle
```

Ahora procedemos a instalar los componentes para tener completamente habilitado nuestro frontend:

```bash
$ npm install
```

***Nota:*** Para habilitar completamente los componentes de front (bootstrap) para formularios vamos a hacer lo siguiente:

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
