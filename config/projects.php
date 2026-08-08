<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Proyectos del portafolio, clave = slug en /proyectos/{slug}
    |--------------------------------------------------------------------------
    */
    'entries' => [
        'brickstarter' => [
            'title' => 'Brickstarter',
            'excerpt' => 'Plataforma de inversión en apartamentos turísticos: catálogo de oportunidades, onboarding con KYC, gestión de fondos e inversiones, y backoffice operativo para un operador fintech inmobiliario.',
            'index' => '01',
            'color' => '#e3f7fa',
            'tags' => ['Laravel', 'Livewire', 'Filament', 'Flux', 'Tailwind', 'Vite', 'Crowdfunding', 'Fintech', 'Real estate', 'KYC', 'Sumsub', 'Marketplace', 'i18n', 'Redis', 'Migration'],
            'role' => 'Full-stack · migración y producto',
            'year' => '2024',
            'client' => 'Brickstarter',
            'url' => 'https://brickstarter.com',
            'sections' => [
                [
                    'title' => 'Sobre el proyecto',
                    'body' => [
                        'Brickstarter.com es la plataforma de crowdfunding inmobiliario de Brickstarter, orientada a la inversión en apartamentos turísticos. Combina un sitio corporativo y de captación con un panel de inversores (cartera, fondos, marketplace, KYC) y un backoffice Filament para la operación diaria del negocio.',
                        'El proyecto destaca por la migración de un monolito Laravel 5.4 a un stack actual (PHP 8.3, Laravel 13, Livewire 4, Flux, Filament 5, Vite y Tailwind 4), manteniendo el dominio crítico: propiedades, inversiones, balances, depósitos/retiradas, AutoInvest, marketplace secundario y cumplimiento (KYC con Sumsub, 2FA, roles/permisos). Técnicamente prioriza clean architecture (controllers finos, Actions/Services, validación y policies), multiidioma y una UI reactiva sin convertir el producto en una SPA.',
                    ],
                ],
                [
                    'title' => 'Qué incluye la web',
                    'body' => [
                        'Sitio público: home, cómo funciona, quiénes somos, FAQ, legales y contacto; listado y ficha de inmuebles / oportunidades; blog con categorías, SEO y feed; newsletter y captación de leads; multiidioma ES/EN con rutas localizadas.',
                        'Panel del inversor: dashboard patrimonial, mis inversiones y evolución de la cartera; fondos (ingreso, retirada, cuentas bancarias, movimientos); marketplace secundario (órdenes, historial, autoinvest); KYC con Sumsub WebSDK; perfil, referidos, certificaciones y 2FA.',
                        'Backoffice Filament: CRUD de inmuebles (estados, tipos, promotores, evolución, estimaciones, medios); usuarios, roles y permisos; inversiones, depósitos, retiradas, remesas, vouchers y pagos; KYC, balances, AutoInvest, newsletter, FAQ y blog; logs de email y de aplicación.',
                    ],
                ],
                [
                    'title' => 'Reto y solución',
                    'body' => [
                        'El reto era modernizar una plataforma fintech con alta densidad de reglas de negocio (inversión, fondos, marketplace, compliance) sin romper la operativa. La solución separa claramente tres superficies (público, inversor, admin), concentra la lógica en Actions/Services, y sustituye el acoplamiento legacy al proveedor de pagos por un modelo interno de cuenta/ledger preparado para adapters.',
                        'El KYC se integra con Sumsub; el catálogo y el panel usan Livewire para flujos reactivos sin SPA completa; el admin se unifica en Filament para operaciones diarias (inmuebles, validaciones, fondos, contenido).',
                    ],
                ],
                [
                    'title' => 'Stack técnico',
                    'body' => [
                        'PHP 8.3 · Laravel 13.15 · Livewire 4.3 · Flux 2.13 · Filament 5.4 · Laravel Fortify · Vite 8 · Tailwind CSS 4 · Alpine.js (vía Flux/Livewire). Multiidioma (mcamara/laravel-localization), Sumsub KYC (paquetes propios), DomPDF, PhpSpreadsheet, PhpWord, Doctrine DBAL, Redis (Predis), reCAPTCHA, SEO / sitemaps y newsletter.',
                        'Arquitectura: Actions / Services · Policies · Jobs · Form Requests. Migración: Laravel 5.4 → Laravel 13 · Mix → Vite · admin custom → Filament 5 · desacoplamiento LemonWay → modelo financiero interno.',
                    ],
                ],
                [
                    'title' => 'Mi aportación',
                    'body' => [
                        'En Brickstarter participé en el desarrollo full-stack de la migración y del producto: arquitectura Laravel moderna, panel Filament, panel de inversor Livewire/Flux, flujos de fondos/inversiones/marketplace, integración KYC Sumsub, multiidioma y backlog operativo del dominio inmobiliario.',
                    ],
                ],
            ],
            'images' => [
                [
                    'src' => '/images/proyectos/brickstarter/brickstarted-screen-1.jpg',
                    'alt' => 'Vista principal de Brickstarter',
                    'hero' => true,
                ],
                [
                    'src' => '/images/proyectos/brickstarter/brickstarted-screen-2.jpg',
                    'alt' => 'Oportunidades de inversión en Brickstarter',
                ],
                [
                    'src' => '/images/proyectos/brickstarter/brickstarted-screen-3.jpg',
                    'alt' => 'Panel de inversores y cartera',
                ],
                [
                    'src' => '/images/proyectos/brickstarter/brickstarted-screen-4.jpg',
                    'alt' => 'Interfaz de Brickstarter',
                ],
            ],
        ],
        'peronda' => [
            'title' => 'Peronda',
            'excerpt' => 'Web oficial de Peronda: exploración de colecciones y piezas, contenidos de marca y captación de leads para un fabricante cerámico internacional.',
            'index' => '02',
            'color' => '#e8f38c',
            'tags' => ['Laravel', 'Livewire', 'Vue', 'Product catalog', 'Filters', 'i18n', 'CRM', 'Mailchimp', 'AWS', 'S3'],
            'role' => 'Front, catálogo e integraciones',
            'year' => '2023',
            'client' => 'Peronda Group',
            'url' => 'https://peronda.com',
            'sections' => [
                [
                    'title' => 'Sobre el proyecto',
                    'body' => [
                        'Peronda.com es la web corporativa y de catálogo de Peronda, marca cerámica del grupo con sede en España y presencia internacional. El objetivo era dar a profesionales del sector una herramienta clara para explorar productos: colecciones y piezas filtrables, fichas técnicas descargables, contenidos de acabados y tecnología, y un canal de contacto/newsletter conectado a marketing y CRM.',
                        'El sitio no es un ecommerce, sino un catálogo B2B/B2C de alta densidad de producto, con datos alimentados desde Peronda Cloud. Técnicamente se apoya en Laravel 9, Livewire 2 y Vue 2, dentro de una arquitectura multi-dominio compartida con las otras marcas del grupo, manteniendo identidad visual, rutas y lógica de negocio específicas de Peronda.',
                    ],
                ],
                [
                    'title' => 'Qué incluye la web',
                    'body' => [
                        'Home con novedades, colecciones destacadas y acceso al catálogo. Listados de colecciones y piezas con filtros por estilo, estancia, tipo de producto, cuerpo/pasta y acabados.',
                        'Ficha de colección (ambientes, despiece, relacionadas, vídeo, descargas) y ficha de pieza (formatos, colores, packaging, PDF y gráficas). Páginas de acabados y tecnología: Deep Tech, Soft, Shaped, Honed, Layer Tech, Extra White, 4D Tech…',
                        'Catálogos estacionales, recursos 3D, dossier y about (marca / Belossa). News y eventos, newsletter, contacto con captación de información de producto (reCAPTCHA) y multiidioma.',
                    ],
                ],
                [
                    'title' => 'Reto y solución',
                    'body' => [
                        'El volumen de producto y la necesidad de datos siempre actualizados se resuelven sincronizando el catálogo con Peronda Cloud (importaciones artisan). La UI combina Blade con componentes interactivos (Livewire / Vue) para buscadores, grids y popups de pieza sin recargar toda la página.',
                        'El contacto se orquesta con mail interno, suscripción Mailchimp y jobs a CRM.',
                    ],
                ],
                [
                    'title' => 'Stack técnico',
                    'body' => [
                        'PHP 8.2 · Laravel 9.52 · Livewire 2.12 · Vue 2.7 · Laravel Mix 6, Sass/Stylus, jQuery. Multiidioma (mcamara/laravel-localization), Media / S3, Redis queues, Mailchimp, reCAPTCHA, SEO tools y sitemaps. Infraestructura AWS y sync con Peronda Cloud.',
                    ],
                ],
                [
                    'title' => 'Mi aportación',
                    'body' => [
                        'En Peronda participé en el desarrollo y mantenimiento del front, catálogo/filtros, formularios de contacto/newsletter e integraciones. Trabajé con Laravel, Livewire y Vue sobre la arquitectura multi-site del grupo: vistas y flujos propios de la marca, datos de producto desde Peronda Cloud, y contacto/newsletter conectados a Mailchimp y CRM.',
                        'Parte de la plataforma multi-marca de Peronda Group (Laravel 9): un codebase, varios dominios, catálogo sincronizado con Peronda Cloud e infraestructura AWS.',
                    ],
                ],
            ],
            'images' => [
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot.jpg',
                    'alt' => 'Vista principal de Peronda',
                    'hero' => true,
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-1.png',
                    'alt' => 'Captura de Peronda',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-2.png',
                    'alt' => 'Detalle de producto Peronda',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-3.png',
                    'alt' => 'Catálogo de cerámicas Peronda',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-4.png',
                    'alt' => 'Experiencia de navegación Peronda',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-5.png',
                    'alt' => 'Interfaz de Peronda',
                ],
            ],
        ],
        'harmony' => [
            'title' => 'Harmony Inspire',
            'excerpt' => 'Sitio digital de Harmony Inspire: colecciones con diseñadores, journal y experiencia visual orientada a la inspiración.',
            'index' => '03',
            'color' => '#f0d9c8',
            'tags' => ['Laravel', 'Livewire', 'Vue', 'Brand website', 'Design catalog', 'CMS content', 'i18n', 'Newsletter', 'AWS'],
            'role' => 'Front, formularios e integración multi-marca',
            'year' => '2023',
            'client' => 'Peronda Group',
            'url' => 'https://harmonyinspire.com',
            'sections' => [
                [
                    'title' => 'Sobre el proyecto',
                    'body' => [
                        'Harmony Inspire es la marca de Peronda Group orientada al diseño y la inspiración. Su web presenta colecciones cerámicas con un enfoque más editorial: protagonismo de designers, ambientes y journal, sin perder la capacidad de consultar producto y descargar material comercial.',
                        'El proyecto forma parte de la plataforma multi-site del grupo, pero Harmony tiene front, rutas y experiencia propios. El catálogo llega desde Peronda Cloud; el contenido de marca (journal, about, designers) se gestiona en el CMS. Stack: Laravel 9, Livewire 2 y Vue 2, con media en S3, multiidioma y captación de leads vía Mailchimp/CRM.',
                    ],
                ],
                [
                    'title' => 'Qué incluye la web',
                    'body' => [
                        'Home editorial con colecciones destacadas (colaboraciones con estudios de diseño). Grid de colecciones / projects con filtros por designers, aspectos, materiales y formatos.',
                        'Ficha de colección: la colección, ambientes, piezas, galería, designer y contacto. Sección Designers (listado + ficha con intro, vídeo y colecciones asociadas). The Journal: archivo, single y tags.',
                        'Catálogos (grid + visualizador), recursos 3D, about, stand virtual, newsletter y formularios de contacto (marca propia → Mailchimp / CRM). Multiidioma.',
                    ],
                ],
                [
                    'title' => 'Reto y solución',
                    'body' => [
                        'Equilibrar inspiración (imágenes, designers, journal) con utilidad de producto (descargas, ficha, contacto). Se modeló una navegación por colecciones y autores, con páginas de proyecto ricas en media.',
                        'Los leads y newsletters se gestionan con el módulo de contacto multi-marca (Livewire, reCAPTCHA, jobs asíncronos).',
                    ],
                ],
                [
                    'title' => 'Stack técnico',
                    'body' => [
                        'PHP 8.2 · Laravel 9.52 · Livewire 2.12 · Vue 2.7 · Laravel Mix, Sass/Stylus. Media library / S3, i18n, SEO, sitemaps. Mailchimp (lista Harmony), colas Redis, AWS. Catálogo sincronizado vía import:harmony desde Peronda Cloud.',
                    ],
                ],
                [
                    'title' => 'Mi aportación',
                    'body' => [
                        'En Harmony participé en el front (colecciones, designers, journal), formularios, newsletter e integración con el ecosistema multi-marca del grupo. Trabajé con Laravel, Livewire y Vue: vistas y flujos propios de la marca, datos de producto desde Peronda Cloud, y contacto/newsletter conectados a Mailchimp y CRM.',
                        'Parte de la plataforma multi-marca de Peronda Group (Laravel 9): un codebase, varios dominios, catálogo sincronizado con Peronda Cloud e infraestructura AWS.',
                    ],
                ],
            ],
            'images' => [
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot.jpg',
                    'alt' => 'Vista principal de Harmony Inspire (placeholder)',
                    'hero' => true,
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-1.png',
                    'alt' => 'Captura de Harmony Inspire (placeholder)',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-2.png',
                    'alt' => 'Detalle Harmony Inspire (placeholder)',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-3.png',
                    'alt' => 'Catálogo Harmony Inspire (placeholder)',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-4.png',
                    'alt' => 'Navegación Harmony Inspire (placeholder)',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-5.png',
                    'alt' => 'Interfaz Harmony Inspire (placeholder)',
                ],
            ],
        ],
        'museum' => [
            'title' => 'Museum Surfaces',
            'excerpt' => 'Sitio de Museum: marca premium, tecnología 4D, línea Elevate y experiencias digitales para ferias y showroom.',
            'index' => '04',
            'color' => '#dce5eb',
            'tags' => ['Laravel', 'Livewire', 'Vue', 'Premium brand', 'Product catalog', 'Landing / Elevate', 'Virtual showroom', 'i18n', 'AWS'],
            'role' => 'Front Museum, Elevate e integraciones',
            'year' => '2023',
            'client' => 'Peronda Group',
            'url' => 'https://museumsurfaces.com',
            'sections' => [
                [
                    'title' => 'Sobre el proyecto',
                    'body' => [
                        'Museum Surfaces es la marca premium de Peronda Group. Su web comunica superficies de alta gama e innovación (4D Tech, Elevate) y ofrece un catálogo de colecciones para profesionales del diseño y la arquitectura.',
                        'Además del producto, el sitio incluye experiencias digitales ligadas a ferias y showroom (stands virtuales, Cevisama/Cersaie/Coverings), journal y flujos de contacto/garantía. Técnicamente comparte la plataforma Laravel multi-tenant del grupo, con front y módulos específicos de Museum, catálogo sincronizado con Peronda Cloud, media en S3 y captación de leads con Mailchimp y CRM vía colas.',
                    ],
                ],
                [
                    'title' => 'Qué incluye la web',
                    'body' => [
                        'Home premium con nuevas colecciones y storytelling de marca. Catálogo de colecciones (filtros por designers / aspectos) y ficha de producto (ambientes, piezas, decorados, descargas).',
                        'Elevate: línea de grandes placas (encimeras/revestimientos), colecciones 4D (Absolute, Infinity, Metal, Arabescato, Venatino…), galerías Vue, despieces y descargas. Página 4D Tech, brand/about y journal editorial.',
                        'Catálogos + visualizador, showroom virtual, stands virtuales (Cersaie, Cevisama, Coverings…), formulario de warranty, contacto y newsletter (lista Mailchimp Museum). Multiidioma.',
                    ],
                ],
                [
                    'title' => 'Reto y solución',
                    'body' => [
                        'Museum necesita vender imagen premium y a la vez explicar tecnologías (4D, Elevate 12 mm). Por eso hay micrositios/secciones con layouts propios (Elevate), mucho media y componentes Vue para galerías y piezas.',
                        'Ferias y showrooms virtuales amplían el sitio más allá del catálogo estático. Datos de producto desde Peronda Cloud (import:museum); leads y CRM en background.',
                    ],
                ],
                [
                    'title' => 'Stack técnico',
                    'body' => [
                        'PHP 8.2 · Laravel 9.52 · Livewire 2.12 · Vue 2.7 · Laravel Mix, Sass/Stylus. i18n, SEO, Media/S3, Redis queues, Mailchimp Museum, reCAPTCHA, AWS. Sync Peronda Cloud + páginas especiales (Elevate, virtual stands, warranty).',
                    ],
                ],
                [
                    'title' => 'Mi aportación',
                    'body' => [
                        'En Museum participé en el desarrollo del front (catálogo, Elevate, experiencias virtuales), formularios e integración con la plataforma compartida del grupo. Trabajé con Laravel, Livewire y Vue: vistas y flujos propios de la marca, datos de producto desde Peronda Cloud, y contacto/newsletter conectados a Mailchimp y CRM.',
                        'Parte de la plataforma multi-marca de Peronda Group (Laravel 9): un codebase, varios dominios, catálogo sincronizado con Peronda Cloud e infraestructura AWS.',
                    ],
                ],
            ],
            'images' => [
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot.jpg',
                    'alt' => 'Vista principal de Museum Surfaces (placeholder)',
                    'hero' => true,
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-1.png',
                    'alt' => 'Captura de Museum Surfaces (placeholder)',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-2.png',
                    'alt' => 'Detalle Museum Surfaces (placeholder)',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-3.png',
                    'alt' => 'Catálogo Museum Surfaces (placeholder)',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-4.png',
                    'alt' => 'Navegación Museum Surfaces (placeholder)',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-5.png',
                    'alt' => 'Interfaz Museum Surfaces (placeholder)',
                ],
            ],
        ],
        'apadrina-un-olivo' => [
            'title' => 'Apadrina un Olivo',
            'excerpt' => 'Web y sistema operativo del proyecto Apadrina un Olivo: adopción de olivos abandonados en Oliete, pagos recurrentes, área de padrinos, panel de gestión y API para trabajo de campo.',
            'index' => '05',
            'color' => '#ffe1a1',
            'tags' => ['Laravel', 'Livewire', 'Stripe', 'Cashier', 'Subscriptions', 'Backpack', 'Alpine', 'Tailwind', 'i18n', 'Mailchimp', 'AWS', 'S3', 'Redis', 'API', 'PWA', 'PDF', 'Excel', 'Social impact'],
            'role' => 'Plataforma, pagos e integraciones',
            'year' => '2023',
            'client' => 'Apadrina un Olivo',
            'url' => 'https://apadrinaunolivo.org',
            'sections' => [
                [
                    'title' => 'Sobre el proyecto',
                    'body' => [
                        'Apadrinaunolivo.org es la plataforma digital del proyecto Apadrina un Olivo, con sede operativa en Oliete (Teruel): recuperar olivos abandonados, generar empleo rural y conectar a padrinos con un impacto tangible. El objetivo del producto es convertir una causa solidaria en un sistema estable de adopciones y renovaciones: elegir olivo, pagar (suscripción o puntual), gestionar regalos, recibir aceite/comunicaciones y descargar certificados fiscales.',
                        'Técnicamente es una aplicación Laravel 8 con UI interactiva en Livewire 2, panel administrativo Backpack y front moderno con Alpine.js y Tailwind. Los pagos se gestionan principalmente con Stripe vía Laravel Cashier (webhooks, customers, métodos de pago, suscripciones), con soporte histórico CECA. El ecosistema se completa con Mailchimp, envío transaccional SparkPost, almacenamiento S3, colas Redis, generación de PDF (facturas/certificados), QR de olivos, imports/exports Excel y una API para operación de campo (parcelas, olivos, PWA). El despliegue se apoya en Docker en desarrollo y en AWS (Elastic Beanstalk + CodePipeline) en staging/producción.',
                    ],
                ],
                [
                    'title' => 'Qué incluye la web',
                    'body' => [
                        'Home y páginas de proyecto (economía, social, pueblo, medioambiente, aceite, educa, visitas…). Flujo de apadrinamiento multi-paso (planes, login/registro, datos de envío, pago). Apadrinamiento como regalo (envío, canje por token, gestión del receptor). Renovaciones, cambio de tarjeta y confirmación de pagos Stripe. Cupones / promociones y landing de adopción.',
                        'Área de padrino (Livewire): adopciones, regalos, noticias, datos fiscales, certificados. Certificados de donación e facturas en PDF. QR por olivo y formularios asociados. Multiidioma ES / EN / FR.',
                        'Panel admin (Backpack): usuarios, adopciones, olivos, parcelas, facturas, regalos, cupones, productos, promociones, reporting y herramientas de fix/sync. API versionada (trees, plots, adoptions, users, webhooks Stripe/CECA, endpoints PWA de campo). Newsletter / sincronización Mailchimp.',
                    ],
                ],
                [
                    'title' => 'Reto y solución',
                    'body' => [
                        'El reto es combinar storytelling de impacto con un negocio de suscripciones y logística real (miles de olivos, regalos, renovaciones, facturación y CRM). Se resuelve con Laravel + Cashier/Stripe (y legacy CECA), jobs/colas Redis, webhooks de pago, sincronización Mailchimp y un admin operativo con Livewire para reporting, imports/exports Excel y correcciones de datos.',
                        'El front interactivo (adopción y área privada) vive en Livewire + Alpine/Tailwind; el trabajo de campo se apoya en API PWA e imágenes con metadatos GPS.',
                    ],
                ],
                [
                    'title' => 'Stack técnico',
                    'body' => [
                        'PHP 7.3+/8.x · Laravel 8.83 · Livewire 2.10 · Laravel Cashier 13.16 · Stripe PHP · Backpack CRUD · Alpine.js 3 · Tailwind CSS 3 · Laravel Mix 6 · Sass · jQuery. Multiidioma (mcamara/laravel-localization) · Spatie Translatable / Newsletter (Mailchimp).',
                        'Snappy/wkhtmltopdf · Maatwebsite Excel · Simple QRcode · Short URLs · Redis (Predis) · AWS S3 · CloudWatch · Elastic Beanstalk · CodePipeline · Docker · SparkPost · Invisible reCAPTCHA · wire-elements/modal · Imagick.',
                    ],
                ],
                [
                    'title' => 'Mi aportación',
                    'body' => [
                        'En Apadrina un Olivo participé en el desarrollo y mantenimiento de la plataforma: flujos de adopción/pago/regalos, área de padrino, admin operativo, integraciones Stripe/Mailchimp, API/PWA de campo, facturación/certificados y despliegue AWS.',
                    ],
                ],
            ],
            'images' => [
                [
                    'src' => '/images/proyectos/apadrinaunolivo/apadrinaunolivo-screen.png',
                    'alt' => 'Vista principal de Apadrina un Olivo',
                    'hero' => true,
                ],
                [
                    'src' => '/images/proyectos/apadrinaunolivo/apadrinaunolivo-screen-2.png',
                    'alt' => 'Flujo de apadrinamiento',
                ],
                [
                    'src' => '/images/proyectos/apadrinaunolivo/apadrinaunolivo-screen-3.png',
                    'alt' => 'Área de padrino y seguimiento del olivo',
                ],
                [
                    'src' => '/images/proyectos/apadrinaunolivo/apadrinaunolivo-screen-4.png',
                    'alt' => 'Impacto social y ambiental en Oliete',
                ],
            ],
        ],
        'tiles-in-mind' => [
            'title' => 'Tiles in Mind',
            'excerpt' => 'Tienda online de azulejos TIM: catálogo filtrable, muestras, área profesional con tarifas y checkout multi-pasarela, conectada al ecosistema Peronda/TIM.',
            'index' => '06',
            'color' => '#F2EFE5',
            'tags' => ['Laravel', 'Livewire', 'GetCandy', 'Nova', 'Ecommerce', 'B2B/B2C', 'ERP sync', 'FTP', 'Redsys', 'PayPal', 'Klarna', 'Dachser', 'Tipsa', 'ActiveCampaign', 'Octane', 'Tailwind'],
            'role' => 'Ecommerce, checkout e integraciones ERP',
            'year' => '2022',
            'client' => 'Tiles in Mind / Peronda Group',
            'url' => 'https://tilesinmind.com',
            'sections' => [
                [
                    'title' => 'Sobre el proyecto',
                    'body' => [
                        'Tilesinmind.com es el ecommerce de Tiles in Mind, marca de azulejos orientada tanto a particulares como a profesionales del sector. El objetivo era vender cerámica online con la misma densidad de datos de un catálogo técnico (formatos, acabados, formas, medidas…) y, a la vez, operar como canal de pedido real: muestras, tarifas B2B, presupuestos y sincronización con el ERP TIM/Peronda.',
                        'La plataforma se construyó sobre Laravel 9 y GetCandy, con un front muy basado en Livewire para catálogo, cesta y checkout, y un backoffice dual: Nova para contenidos/CMS y GetCandy Admin Hub para operaciones de pedido. El catálogo, precios y stock se alimentan por importaciones CSV vía FTP Synology; los pedidos se reexportan como CSV hacia carpetas Enviados/Procesados para el flujo interno. En checkout conviven varias pasarelas (Redsys, PayPal, Klarna, transferencia) y la logística distingue muestras (Tipsa) de producto (Dachser). El resultado es un ecommerce cerámico de producción, no un simple catálogo: comercio + ops + integración ERP en un solo producto digital.',
                    ],
                ],
                [
                    'title' => 'Qué incluye la web',
                    'body' => [
                        'Catálogo de colecciones y piezas con filtros (forma, color, medida, acabado, material, aspecto, estancia, uso…). Ficha de producto / colección, fichas técnicas, gráficas y descargas.',
                        'Cesta y checkout (particular y profesional), pedido de muestras y flujo logístico Tipsa. Área profesional: pedidos, cupones, presupuestos PDF, proyectos / ideas. Wishlist, inspiración/blog y páginas CMS (ventajas, FAQs, envíos, financiación…).',
                        'Pagos: tarjeta (Redsys), PayPal, Klarna y transferencia. Hub de pedidos (GetCandy Admin) + CMS (Laravel Nova). Export CSV de pedidos a FTP TIM (Enviados/Procesados) y comprobación/reenvío desde admin.',
                    ],
                ],
                [
                    'title' => 'Reto y solución',
                    'body' => [
                        'El catálogo y los precios cambian con frecuencia; se resuelven con importaciones programadas (import:pricestock, import:procoll) desde CSV TIM. El pedido no termina en la web: se genera un CSV operativo y se sube a Synology para el ERP.',
                        'El front es Livewire-first (fichas, cesta, checkout, filtros) sobre GetCandy; las operaciones de pedido (factura, transportistas, CSV) viven en el Admin Hub.',
                    ],
                ],
                [
                    'title' => 'Stack técnico',
                    'body' => [
                        'PHP 8+ · Laravel 9 · Livewire 2.10 · GetCandy (core + admin) · Laravel Nova 3 · Laravel Mix 6 · Tailwind 3 · Alpine 2 · Sass. Laravel Octane + RoadRunner, Redis / Predis, queues y scheduled jobs.',
                        'Maatwebsite Excel · DomPDF · Spatie Media Library / Translatable · FTP Synology (Flysystem) · AWS S3 · ActiveCampaign · Redsys · PayPal · Klarna · Dachser · Tipsa · Pinterest CSV feed · SEO (romanzipp/laravel-seo) · Log Viewer.',
                    ],
                ],
                [
                    'title' => 'Mi aportación',
                    'body' => [
                        'En Tiles in Mind participé en el desarrollo y mantenimiento del ecommerce: catálogo/filtros, checkout y pasarelas, área profesional, Admin Hub (pedidos, CSV ERP, transportistas), importaciones TIM y jobs de integración. Trabajé con Laravel, Livewire y GetCandy conectando comercio, operaciones y sync ERP en un solo producto digital.',
                    ],
                ],
            ],
            'images' => [
                [
                    'src' => '/images/proyectos/tilesinmind/tilesinmindscreenshot.png',
                    'alt' => 'Vista principal de Tiles in Mind',
                    'hero' => true,
                ],
                [
                    'src' => '/images/proyectos/tilesinmind/tilesinmindscreenshot-2.png',
                    'alt' => 'Catálogo y filtros de Tiles in Mind',
                ],
                [
                    'src' => '/images/proyectos/tilesinmind/tilesinmindscreenshot-3.png',
                    'alt' => 'Checkout y experiencia de compra TIM',
                ],
            ],
        ],
    ],

];
