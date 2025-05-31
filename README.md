## Interfaz del Programa de Aplicación

TaxLab API es una aplicación desarrollada en laravel que incluye autenticación JWT y documentación en el formato Swagger API.  

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


### Pruebas

1. Ir a http://127.0.0.1:8000/api/documentation y ejecutar los endpoints correspondientes.

### Variables de entorno

```c

# APP_NAME
Nombre de la aplicación. Valor : Api Ecommerce

#APP_ENV
Indica el entorno de la aplicacion. Valor: local - test - production

#APP_KEY
Indica el key para encriptar. Valor : base64:srw/tKI1DmZ/y+ItmUt5NuefwqJgEUF1js+ZUXSzf1g=

#APP_DEBUG
Indica si la aplicación muestra el debug. Valor: true

#APP_URL
Url de la aplicación del backend. Sin el / al final.  Ejemplo: http://127.0.0.1:8000 

#APP_URL_FRONT
Url de la aplicación del front. Sin el / al final. Ejemplo: http://localhost:3000

#LOG_CHANNEL
Valor por defecto. Valor: stack. 

LOG_LEVEL
Valor no modificable. Valor: error 

APP_TIMEZONE
Valor no modificable. Valor: America/Santiago

#ENTRY_MODULES
Valor no modificable. Auth,Api,PermissionsRoles

#GET_NAME_APP
Nombre que se muestra en los correos. Valor: Api Ecommerce - Desafío Periferia

#DB_CONNECTION
Valor de conección a la bd. Valor: mysql

#DB_HOST
Valor de host a la bd. Valor: 127.0.0.1

#DB_PORT=3306
Valor del puerto por el que se conecta. Valor: 3306

#DB_DATABASE
Valor del nombre de la bd. Ejemplo: taxlab_bd_local

#DB_USERNAME
Valor de nombre de usuario a la bd. Ejemplo: root

#DB_PASSWORD
Valor de la contraseña a la bd. Ejemplo: 1234

#BROADCAST_DRIVER
Valor por defecto. Valor: log

#CACHE_DRIVER
Valor por defecto. Valor: file

#QUEUE_CONNECTION
Valor por defecto. Valor: database

#SESSION_DRIVER
Valor por defecto. Valor: file

#SESSION_LIFETIME
Valor por defecto. Valor: 120

MEMCACHED_HOST
Valor por defecto. Valor: 127.0.0.1

#REDIS_HOST
Valor por defecto. Valor: 127.0.0.1

#REDIS_PASSWORD=null
Valor por defecto. Valor: null

#REDIS_PORT
Valor por defecto. Valor: 6379

#MAIL_MAILER
Valor por defecto. Valor: smtp

#MAIL_HOST
Valor por defecto. Valor: smtp-relay.brevo.com

#MAIL_PORT
Valor por defecto. Valor: 587

#MAIL_USERNAME
Valor por defecto. Valor: pedrorafael.rojodiaz@gmail.com

#MAIL_PASSWORD
Valor por defecto. Valor: xsmtpsib-d58c45760df2a439171b65e6cbc0e7789e0f65fb353aaaa7c34e8d8630ebc552-EkfRXZQSy0GgcsV2

#MAIL_ENCRYPTION
Valor por defecto. Valor: tls

#MAIL_FROM_ADDRESS
Valor por defecto. Valor: noreply@taxlab.cl

#MAIL_FROM_NAME
Valor por defecto. Valor: "${APP_NAME}"

#PUSHER_APP_ID
Valor por defecto vacío.

#PUSHER_APP_KEY
Valor por defecto vacío.

#PUSHER_APP_SECRET
Valor por defecto vacío.

#PUSHER_APP_CLUSTER
Valor por defecto. Valor: mt1

#MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"

#MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

#L5_SWAGGER_GENERATE_ALWAYS
Valor por defecto true.

#L5_SWAGGER_UI_PERSIST_AUTHORIZATION
Valor por defecto false.

#L5_SWAGGER_UI_DOC_EXPANSION
Valor por defecto list.

#JWT_TTL
Valor por defecto 43200

#JWT_SECRET
Valor por defecto. Valor: KjS58WvaiGjGWWMST2m9n9rlPuu66AvmpWpddd2k1EtSX6M3Vx8szt3MuLWNlt20

#URL_WEBHOOK_DISCORD
Valor por defecto vacío.

#IS_INITIAL=true
Cuando se ejecute la primera migración debe estar en valor true. Despues pasar a false.

#ES_NAME_ROL_ECOMMERCE_USER
Nombre en español del rol Usuario Ecommerce. Valor: "Usuario Ecommerce"

#ES_NAME_ROL_ECOMMERCE_ADMIN
Nombre en español del rol Administrador Ecommerce. Valor: "Administrador Ecommerce"

#EN_NAME_ROL_ECOMMERCE_USER="User Ecommerce"
Nombre en inglés del rol Usuario Ecommerce. Valor: "User Ecommerce"

#ES_NAME_ROL_ECOMMERCE_ADMIN="Administrador Ecommerce"
Nombre en inglés del rol Administrador Ecommerce. Valor: "Administrador Ecommerce"



#PAGINATION_PAGE=1
Página a mostrar en las consultas.Valor por defecto. Valor: 1

#PAGINATION_RECORDS
Número de registros por página. Valor por defecto. Valor: 10

#AWS_ACCESS_KEY_ID
Valor de ID de usuario de IAM en aws.

#AWS_SECRET_ACCESS_KEY
Valor de Pass de usuario de IAM en aws.

#AWS_DEFAULT_REGION
Valor de la región en aws donde se creó el bucket.

#AWS_BUCKET=staging-byg-documents-s3
Valor del bucket que existe por ambiente de producción y test.

#AWS_USE_PATH_STYLE_ENDPOINT
Valor por defecto. Valor: false


```
