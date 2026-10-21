---
paths:
  - 'app/Models/**'
---

# Models

## Scopes published y arrays de vista
Para contenido publicable usá scopePublished. Cuando Livewire necesite un shape de vista, exponelo con toViewArray() o toEntryArray() en el modelo.

## Contenido en inglés con columnas _en
El contenido traducible usa columnas "{campo}_en" y el trait HasEnglishTranslations. En toViewArray()/toEntryArray() usá $this->translated('campo'), que devuelve el inglés en /en y cae al español si está vacío. Agregá el campo _en también en el formulario de Filament, dentro de las pestañas de idioma (ver .ai/rules/resources.md).
