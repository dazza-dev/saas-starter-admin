# SaaS Starter Admin — Development Rules

## Stack

- Laravel 13, PHP 8.3, Filament 5
- Multitenancy via Stancl Tenancy (database-per-tenant)
- This project is the **owner of the whole database schema**: both the central DB and the tenant DBs

---

## Comments (Comentarios)

- **Language:** every comment is written in **Spanish**. The code itself — names of variables, functions, classes, methods, fields, DB columns, types — stays in **English**.
- **Function / method / class docblocks:** always use the **multi-line block** form spanning **at least 3 lines**.
- **Inline comments inside a function body:** a single-line `//` comment is fine.

---

## Who owns what

| Concern                  | Lives here                             |
| ------------------------ | -------------------------------------- |
| Central DB migrations    | `database/migrations/`                 |
| Central DB seeders       | `database/seeders/` + `database/data/` |
| **Tenant** DB migrations | `tenant/migrations/`                   |
| **Tenant** DB seeders    | `tenant/seeders/` + `tenant/data/`     |
| Tenant CRUD (Filament)   | `app/Filament/Resources/`              |
| Tenant provisioning jobs | `app/Jobs/`                            |

`saas-starter-api` has **no** `database/` directory on purpose. Never add migrations there.

---

## Central vs tenant

**Central DB** — one, shared. Holds:

- `tenants`, `domains` — the tenant registry (stancl/tenancy)
- `tenant_types` — what kind of business each tenant runs (clothing shop, supermarket…), not a pricing plan
- `modules`, `permissions` — the permission catalog, one fixed list shared by every tenant
- `users`, `password_reset_tokens` — the operators of **this admin panel** (not the tenants' users) and their password resets

Navigation is not in the database: each sidebar is a plain TypeScript file in the SPA.

**Tenant DB** — one per tenant, named `tenant-{id}`. Holds:

- `users` — the people who log into the SaaS application
- `roles`, `role_user`, `permission_role`, `permission_user` — the ACL and its pivots
- `groups`, `settings`, `logs`, `sessions`, `personal_access_tokens`
- `password_reset_tokens` — resets for the tenant's own users; each tenant keeps its own, never the central DB

The permission **catalog** is central but its **assignments** are per tenant. Eloquent cannot join across connections, so `saas-starter-api`'s `PermissionService` resolves that crossing with explicit two-step queries. Keep it that way.

The catalog is the same for every tenant: there is no per-tenant customisation, so it carries no `tenant_id` and no role-derived rows.

---

## Tenant provisioning

Creating a `Tenant` fires `TenantCreated`, whose listener chain (in `TenancyServiceProvider::events()`) runs:

1. `Jobs\CreateDatabase` — creates `tenant-{id}`
2. `Jobs\MigrateDatabase` — runs `tenant/migrations/`
3. `Jobs\SeedDatabase` — runs `Tenant\Seeders\TenantDatabaseSeeder`
4. `App\Jobs\CreateTenantAdminUser` — creates the first admin user from the tenant's contact data
5. `App\Jobs\UpdateTenantSettings` — copies name/email into the tenant's `settings`

The chain runs on the queue. In local development set `QUEUE_CONNECTION=sync` (or run a worker) or the tenant DB is never provisioned.

Deleting a tenant drops its database (`Jobs\DeleteDatabase`).

---

## Migration rules

- Tenant migrations are numbered so that dependencies come first: `acl` (roles) → `groups` → `users` → the rest. Keep that order when adding tables with foreign keys
- The tenant `users` table is the contract with `saas-starter-api`'s `App\Modules\Users\Models\User`. Adding a column there means adding it to that model's `#[Fillable]` and to `UserResource`
- `users.status` is duplicated in code: keep it in sync with the API's `UserRequest` rules and the SPA's `UserForm` status options

---

## Seed data

Seeders read plain JSON from `database/data/` and `tenant/data/`, so changing the seeded content never means touching PHP:

| File                             | Seeds                                                                                          |
| -------------------------------- | ---------------------------------------------------------------------------------------------- |
| `database/data/Modules.json`     | The modules permissions are grouped under                                                      |
| `database/data/Permissions.json` | The permission catalog: `name`, `module`, `group`, `order`                                     |
| `database/data/TenantTypes.json` | Business types. The shipped ones are placeholders: replace them with your SaaS's own verticals |
| `tenant/data/Roles.json`         | Roles created in every new tenant                                                              |
| `tenant/data/Groups.json`        | Groups created in every new tenant                                                             |
| `tenant/data/Settings.json`      | The settings row set of every new tenant                                                       |

**Adding a permission** means adding it to `Permissions.json` **and** to the three `names.php` translation files in `saas-starter-api/app/Modules/Configs/Permissions/Lang/{en,es,pt}/`, under `actions` (plus `groups` or `modules` if either is new). A permission with no translation renders as its raw key in the permissions matrix.

`module` is nullable: a permission that does not belong to any module, like the profile ones, lands in the `general` tab.

`Roles.json` must stay in sync with `Role::SYSTEM_ROLES` in the API: a slug listed there cannot be renamed or deleted by users, and the `admin` slug specifically is what `AclSeeder` grants every permission to and what `User::ROLE_ADMIN` compares against.

---

## Editing the catalog from the panel

`ModuleResource` and `PermissionResource` allow full CRUD, so the JSON files seed the
initial catalog but are not the only way to change it. Two consequences:

- A permission created here has no entry in the API's `names.php`, so its label falls back
  to a humanised version of the key (`read-invoices` renders as "Read Invoices"). Add the
  translation to get a proper label
- `permission_role` and `permission_user` live in the tenant databases and cannot have a
  foreign key to the central catalog, so deleting a permission leaves orphan rows behind.
  They grant nothing — the id no longer resolves and MySQL never reuses an autoincrement —
  so the starter leaves them there. A real product should sweep them with a scheduled
  command that iterates the tenants and deletes the pivot rows whose permission_id is gone

---

## Filament Resources

- `TenantResource` — the main screen: tenant data, its domains (repeater) and, on create, the credentials of the admin user to provision
- `TenantTypeResource` — business types
- `ModuleResource`, `PermissionResource` — the permission catalog, editable
- `BaseModalResource` — the shared base for simple catalogs edited in a modal instead of a full page. Extend it for new lookup tables

---

## Before committing

```bash
./vendor/bin/pint    # code style
php artisan test     # Pest
```

---

## Related projects

- `saas-starter-api` — the Laravel API that reads this schema
- `saas-starter-app` — the Vue 3 SPA
