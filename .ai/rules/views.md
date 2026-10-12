---
paths:
  - 'resources/views/**'
---

# Views

## Componentes Blade anónimos
Compone la UI con componentes Blade anónimos <x-*> en resources/views/components. Evitá class-based View Components y nuevos @include salvo el patrón mínimo ya existente.

## Textos traducibles y enlaces por idioma
El sitio público existe en español (sin prefijo) y en inglés (/en). Escribí los textos fijos en español dentro de __('...') y agregá su traducción en lang/en.json. Para enlazar páginas públicas usá localized_route('nombre'), no route(), así el enlace queda en el idioma actual.

## Menú del header
Los enlaces del menú desplegable del header salen de App\Support\SiteMenu::groups(); para sumar uno, agregalo ahí con su icono de lucide. Los posts destacados se marcan en Filament con "Destacar en el menú" (se muestran los 2 más recientes).
