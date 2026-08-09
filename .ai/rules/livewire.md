---
paths:
  - 'app/Livewire/**'
---

# Livewire

## Formato Livewire class-based
Usá componentes Livewire class-based con Blade separado. En full-page aplicá #[Layout] y #[Title] y devolvé view(...) desde render(). No uses Volt ni single-file components.

## Eloquent directo sin repositorios
Consultá Eloquent en el call site (Livewire, Actions, Support). No introduzcas capas Repositories o Queries.
