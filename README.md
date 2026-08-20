# saas-starter-admin

Panel de administración central del starter kit: gestiona los tenants, provisiona su base de datos
y es el **dueño de todo el esquema** — tanto de la base de datos central como de la de cada tenant.

Laravel 13 · Filament 5 · stancl/tenancy · MySQL

---

## Qué trae

- CRUD de tenants con sus dominios, y aprovisionamiento automático de su base de datos
- Creación del primer usuario administrador de cada tenant
- Migraciones y seeders centrales y de tenant
- Catálogo de permisos agrupados por módulos, y tipos de negocio
- Panel Filament con selector de idioma y recuperación de contraseña

## Requisitos

- PHP 8.3+
- Composer
- MySQL 8+

## Puesta en marcha

```bash
composer install
cp .env.example .env
php artisan key:generate

# Crea la base de datos central antes de migrar
mysql -uroot -p -e "CREATE DATABASE saas_starter"

php artisan migrate --seed
php artisan storage:link

# El tema del panel. `composer install` tiene que haber corrido antes: el CSS
# hace @import del tema base de Filament desde vendor/, y sin esa carpeta el
# build falla al no poder resolverlo.
npm ci
npm run build
```

Crea el usuario del panel:

```bash
php artisan make:filament-user
```

Y arranca:

```bash
php artisan serve --port=8080
```

El panel queda en `http://localhost:8080` (la pantalla de login está en la raíz, no en `/admin`).

> Los correos van al SMTP de Mailpit (`brew install mailpit && brew services start mailpit`); su
> bandeja queda en `http://localhost:8025`.

## Crear un tenant

Desde el panel: **Tenants → Crear**. Rellena el nombre de la organización, el correo y la contraseña
del administrador, y añade al menos un dominio (p. ej. `acme`) — ese valor es el que la SPA envía en
la cabecera `X-Tenant` y el que aparece en su URL (`/acme/`).

Al guardar se dispara la cadena de aprovisionamiento: crear la base `tenant-acme`, migrarla,
sembrarla, crear el usuario administrador y volcar los ajustes iniciales.

> En desarrollo pon `QUEUE_CONNECTION=sync` en tu `.env` o levanta un worker: la cadena corre en la
> cola y sin worker el tenant se crea sin base de datos.

## Estructura

```
app/
├── Filament/Resources/   Tenants, tipos de tenant, módulos, permisos
├── Jobs/                 Aprovisionamiento del tenant
└── Models/               Tenant, TenantType, Module, Permission, User (del panel)

database/
├── migrations/           Esquema de la BD CENTRAL
├── seeders/              Semillas centrales
└── data/                 Datos de las semillas, en JSON

tenant/
├── migrations/           Esquema de la BD de CADA TENANT
├── seeders/              Semillas de cada tenant
└── data/                 Roles, grupos y ajustes iniciales, en JSON
```

Las reglas de desarrollo están en [`CLAUDE.md`](./CLAUDE.md).

## Proyectos relacionados

| Repo               | Qué es                                         |
| ------------------ | ---------------------------------------------- |
| `saas-starter-api` | La API que consume este esquema                |
| `saas-starter-app` | La SPA en Vue 3 que consume la API             |
| `vuetify-app-kit`  | Layouts, tema, componentes y utilidades de app |
