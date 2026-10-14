---
paths:
  - 'app/Filament/Resources/**'
---

# Resources

## Estructura de Filament Resources
Anidá cada Resource en Resources/{Name}/{Name}Resource.php con Pages List/Create/Edit delgadas. Definí form(Schema) y table(Table) inline en el Resource; no crees carpetas Schemas/ ni Tables/ ni RelationManagers salvo necesidad real. Usá labels de navegación en español e iconos Heroicon.

## Textos traducibles en pestañas de idioma
Los campos traducibles ("{campo}" y "{campo}_en") van con App\Filament\Support\LanguageTabs: pestaña Español, una por idioma y el botón "Traducir desde español" (TranslateFromSpanishAction, traduce con Claude). No uses una sección "Inglés" aparte. Pasá en translatableFields los campos en español, incluidos los repetidores de textos. Si hay más de un grupo de pestañas en el mismo formulario, dales un key distinto.

## Completar con IA
Si los textos traducibles son contenido escrito (no solo etiquetas), pasale a LanguageTabs un completion: CompleteWithAiAction::make(about, guidance, context, webSearch, image) con qué es el registro y qué va en cada campo (largo incluido). webSearch solo para datos públicos (herramientas, fuentes, libros), nunca para contenido personal del dueño del sitio; image si hay una foto o portada que ayude.
