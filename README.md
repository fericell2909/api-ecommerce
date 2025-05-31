## Interfaz del Programa de Aplicación

Api Ecommerce es una aplicación desarrollada en laravel que incluye autenticación JWT y documentación en el formato Swagger API para un desafío en periferia-it-pe  

---

### Lenguaje de Programación y Plataforma de Trabajo usada:

1. PHP-8
2. Laravel-9

### Arquitectura usada:

1. Laravel 9.x
2. Patrón Repositorio
3. Modelo de Datos basado en consultas eloquent.
4. Documentación de Endpoints basada en Swagger. 
5. Autenticación basada en Json Web tokens.
6. Estructura de Carpetas para el proyecto basado en Módulos.

### Ejecutar el proyecto en local:

1. Clonar el proyecto -

```bash
    git clone https://github.com/fericell2909/api-ecommerce.git
```

2. Ir a la ruta del proyecto en  `cd api-ecommerce`.
3. Crear un archivo llamado `.env`  y Copiar `.env.example` hacia `.env`
4. Crear una base de datos llamada - `api_-_ecommerce_local`. y modificar las variables en el archivo .env
5. Ejecutar el comando - `composer install`.Debe tener instalado composer.
6. Ejecutar el siguiente comando.

```bash
   php artisan migrate --seed
```

7. Para generar la documentación. Ejecutar el comando siguiente
```bash
php artisan l5-swagger:generate
```

8. Copiar el archivo generado en storage/api-docs/api-docs.json en public/docs/api-docs.json

9. Ejecutar el comando para levantar en local.

```bash
    php artisan serve
```

10. Abrir el Navegador de su preferencia. -
   http://127.0.0.1:8000 

11. Para ver la documentación generada.
   http://127.0.0.1:8000/api/documentation

12. Ejecutar el comando de storage link

```bash
    php artisan storage:link
```

### Pruebas

1. Ir a http://127.0.0.1:8000/api/documentation y ejecutar los endpoints correspondientes.

### Variables de entorno

```c

    Enviar email to send the last .env

```
