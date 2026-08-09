---
paths:
  - 'domains/**'
---

# Domains

## Sitios por dominio multi-host
Poné el código de cada sitio por host en domains/{Studly}/ con namespace Domains\{Studly}\. El provider debe extender App\Support\Domain\DomainServiceProvider y registrarse en config/domains.php bajo sites.{key}.provider.
