---
paths:
  - 'routes/*.php'
---

# Routes

## Páginas del sitio como Livewire full-page
Implementá las páginas del sitio como componentes Livewire class+view y registralas con Route::livewire(..., ::class), no con controllers HTTP de página.

## Rutas públicas en dos idiomas
Las páginas públicas se registran dentro de $registerSiteRoutes en routes/web.php: una vez en español con middleware locale:es y otra bajo /en con nombres "en.*", slugs en inglés y middleware locale:en. Toda página pública nueva se agrega ahí con su path en ambos idiomas.
