---
paths:
  - 'app/Actions/**'
---

# Actions

## Eloquent directo sin repositorios
Consultá Eloquent en el call site (Livewire, Actions, Support). No introduzcas capas Repositories o Queries.

## Actions con handle sin Services
Las Actions de aplicación usan método handle(). No crees una capa Services/ paralela para el mismo trabajo.
