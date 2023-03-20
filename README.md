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

Por último, vamos a ejecutar este archivo de migracion para que se generen las tablas en nuestra BBDD:

```bash
$ php bin/console doctrine:migrations:migrate
```