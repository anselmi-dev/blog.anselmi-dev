# Dominios multi-host

Soporte de subdominios / dominios adicionales sin mezclar lógica con el portfolio principal (`app/` + `routes/web.php`).

## Idea

1. Registrás el dominio en **`config/domains.php`** con su `provider`, `hosts` y `connection` (opcional).
2. El **ServiceProvider del dominio** carga rutas, vistas, migraciones y Livewire.
3. El portfolio **no se mueve**: cualquier host no listado sigue sirviendo la web principal.

## Estructura

```
domains/
  Test/
    TestServiceProvider.php
    config/domain.php
    routes/web.php
    Livewire/
    Http/Controllers/
    Models/
    database/migrations/
    resources/views/
    resources/css/app.css   # @import shared.css
    resources/js/app.js
```

Namespace Composer: `Domains\` → `domains/`.

## Registro (`config/domains.php`)

```php
'sites' => [
    'test' => [
        'provider' => \Domains\Test\TestServiceProvider::class,
        'hosts' => ['test.anselmidev.test'],
        'connection' => null, // o 'test' si tiene DB propia
    ],
],
```

## Crear un dominio nuevo

```bash
php artisan domain:make Shop --hosts=shop.anselmidev.test
# con DB propia:
php artisan domain:make Shop --hosts=shop.test --database=shop
composer dump-autoload
npm run build
```

Luego:

1. Agregar/confirmar env `DOMAIN_SHOP_HOSTS=...`
2. Si usás DB propia: conexión en `config/database.php` + `php artisan domain:migrate shop`
3. Vhost / Laragon apuntando al mismo `public/` con el host del dominio

## Base de datos

| `connection` | Comportamiento |
|---|---|
| `null` | Comparte `DB_CONNECTION` global |
| `'shop'` | `IdentifyDomain` setea esa conexión en el request |

Modelos del dominio pueden usar `App\Models\Concerns\UsesDomainConnection`.

## Componentes compartidos

Las vistas del dominio pueden usar Blade del portfolio (`<x-icon>`, etc.) porque Vite/`@source` incluye `resources/views/components` y el layout puede renderizar esos componentes.

Preferí vistas namespaced: `test::livewire.home`, `test::layouts.domain`.

## Tailwind

- Portfolio: `resources/css/app.css` (sin cambios obligatorios)
- Compartido: `resources/css/shared.css`
- Dominio: `domains/{Name}/resources/css/app.css` importa `shared.css` y puede overridear `@theme`

Vite descubre automáticamente `domains/*/resources/{css,js}/app.*`.

## Comandos

- `php artisan domain:make {Name}`
- `php artisan domain:migrate {key}`

## Checklist nuevo dominio

- [ ] `domains/{Name}/` con provider + rutas + vistas
- [ ] Entrada en `config/domains.php`
- [ ] Variable `DOMAIN_*_HOSTS` en `.env`
- [ ] `composer dump-autoload`
- [ ] Vite build / dev
- [ ] Host local apuntando a `public/`
- [ ] DB propia solo si hace falta (`connection` + migrate)
