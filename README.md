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