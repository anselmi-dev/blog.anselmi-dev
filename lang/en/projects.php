<?php

/*
|--------------------------------------------------------------------------
| Traducción al inglés de config/projects.php
|--------------------------------------------------------------------------
|
| Misma clave (slug) que en config/projects.php. Solo incluye los textos: el
| resto de la ficha (tags, año, color, imágenes) se toma del config. "sections"
| y "image_alts" siguen el mismo orden que en el config.
|
*/

return [

    'brickstarter' => [
        'excerpt' => 'Investment platform for holiday apartments: opportunity catalog, onboarding with KYC, fund and investment management, and an operations back office for a real-estate fintech operator.',
        'role' => 'Full-stack · migration and product',
        'sections' => [
            [
                'title' => 'About the project',
                'body' => [
                    'Brickstarter.com is Brickstarter’s real-estate crowdfunding platform, focused on investing in holiday apartments. It combines a corporate and lead-generation site with an investor dashboard (portfolio, funds, marketplace, KYC) and a Filament back office for the day-to-day running of the business.',
                    'The project stands out for migrating a Laravel 5.4 monolith to a current stack (PHP 8.3, Laravel 13, Livewire 4, Flux, Filament 5, Vite and Tailwind 4) while keeping the critical domain intact: properties, investments, balances, deposits/withdrawals, AutoInvest, secondary marketplace and compliance (KYC with Sumsub, 2FA, roles/permissions). Technically it prioritizes clean architecture (thin controllers, Actions/Services, validation and policies), multi-language support and a reactive UI without turning the product into an SPA.',
                ],
            ],
            [
                'title' => 'What the site includes',
                'body' => [
                    'Public site: home, how it works, about us, FAQ, legal pages and contact; property / opportunity listings and detail pages; blog with categories, SEO and feed; newsletter and lead capture; ES/EN multi-language with localized routes.',
                    'Investor dashboard: wealth dashboard, my investments and portfolio performance; funds (deposits, withdrawals, bank accounts, transactions); secondary marketplace (orders, history, autoinvest); KYC with Sumsub WebSDK; profile, referrals, certificates and 2FA.',
                    'Filament back office: property CRUD (statuses, types, developers, performance, estimates, media); users, roles and permissions; investments, deposits, withdrawals, remittances, vouchers and payments; KYC, balances, AutoInvest, newsletter, FAQ and blog; email and application logs.',
                ],
            ],
            [
                'title' => 'Challenge and solution',
                'body' => [
                    'The challenge was to modernize a fintech platform with a dense set of business rules (investment, funds, marketplace, compliance) without disrupting operations. The solution clearly separates three surfaces (public, investor, admin), concentrates the logic in Actions/Services, and replaces the legacy coupling to the payment provider with an internal account/ledger model ready for adapters.',
                    'KYC is integrated with Sumsub; the catalog and the dashboard use Livewire for reactive flows without a full SPA; the admin is unified in Filament for daily operations (properties, validations, funds, content).',
                ],
            ],
            [
                'title' => 'Tech stack',
                'body' => [
                    'PHP 8.3 · Laravel 13.15 · Livewire 4.3 · Flux 2.13 · Filament 5.4 · Laravel Fortify · Vite 8 · Tailwind CSS 4 · Alpine.js (via Flux/Livewire). Multi-language (mcamara/laravel-localization), Sumsub KYC (in-house packages), DomPDF, PhpSpreadsheet, PhpWord, Doctrine DBAL, Redis (Predis), reCAPTCHA, SEO / sitemaps and newsletter.',
                    'Architecture: Actions / Services · Policies · Jobs · Form Requests. Migration: Laravel 5.4 → Laravel 13 · Mix → Vite · custom admin → Filament 5 · decoupling from LemonWay → internal financial model.',
                ],
            ],
            [
                'title' => 'My contribution',
                'body' => [
                    'At Brickstarter I worked on the full-stack development of the migration and the product: modern Laravel architecture, Filament panel, Livewire/Flux investor dashboard, funds/investments/marketplace flows, Sumsub KYC integration, multi-language support and the operational backlog of the real-estate domain.',
                ],
            ],
        ],
        'image_alts' => [
            'Brickstarter home page',
            'Investment opportunities on Brickstarter',
            'Investor dashboard and portfolio',
            'Brickstarter interface',
        ],
    ],

    'peronda' => [
        'excerpt' => 'Peronda’s official website: browsing collections and pieces, brand content and lead capture for an international ceramics manufacturer.',
        'role' => 'Front end, catalog and integrations',
        'sections' => [
            [
                'title' => 'About the project',
                'body' => [
                    'Peronda.com is the corporate and catalog website of Peronda, the group’s ceramics brand, headquartered in Spain with an international presence. The goal was to give industry professionals a clear tool to explore products: filterable collections and pieces, downloadable technical sheets, content about finishes and technology, and a contact/newsletter channel connected to marketing and the CRM.',
                    'The site is not an e-commerce store but a product-dense B2B/B2C catalog, with data fed from Peronda Cloud. Technically it relies on Laravel 9, Livewire 2 and Vue 2, within a multi-domain architecture shared with the group’s other brands, while keeping Peronda’s own visual identity, routes and business logic.',
                ],
            ],
            [
                'title' => 'What the site includes',
                'body' => [
                    'Home page with new releases, featured collections and access to the catalog. Collection and piece listings with filters by style, room, product type, body and finishes.',
                    'Collection page (settings, layout, related collections, video, downloads) and piece page (formats, colors, packaging, PDF and graphics). Finish and technology pages: Deep Tech, Soft, Shaped, Honed, Layer Tech, Extra White, 4D Tech…',
                    'Seasonal catalogs, 3D resources, dossier and about (brand / Belossa). News and events, newsletter, contact with product information requests (reCAPTCHA) and multi-language support.',
                ],
            ],
            [
                'title' => 'Challenge and solution',
                'body' => [
                    'The volume of products and the need for always up-to-date data are handled by syncing the catalog with Peronda Cloud (Artisan imports). The UI combines Blade with interactive components (Livewire / Vue) for search, grids and piece pop-ups without reloading the whole page.',
                    'Contact requests are orchestrated through internal mail, Mailchimp subscriptions and CRM jobs.',
                ],
            ],
            [
                'title' => 'Tech stack',
                'body' => [
                    'PHP 8.2 · Laravel 9.52 · Livewire 2.12 · Vue 2.7 · Laravel Mix 6, Sass/Stylus, jQuery. Multi-language (mcamara/laravel-localization), Media / S3, Redis queues, Mailchimp, reCAPTCHA, SEO tools and sitemaps. AWS infrastructure and sync with Peronda Cloud.',
                ],
            ],
            [
                'title' => 'My contribution',
                'body' => [
                    'At Peronda I worked on developing and maintaining the front end, catalog/filters, contact/newsletter forms and integrations. I worked with Laravel, Livewire and Vue on the group’s multi-site architecture: the brand’s own views and flows, product data from Peronda Cloud, and contact/newsletter connected to Mailchimp and the CRM.',
                    'Part of Peronda Group’s multi-brand platform (Laravel 9): one codebase, several domains, a catalog synced with Peronda Cloud and AWS infrastructure.',
                ],
            ],
        ],
        'image_alts' => [
            'Peronda home page',
            'Peronda screenshot',
            'Peronda product detail',
            'Peronda ceramics catalog',
            'Browsing experience on Peronda',
            'Peronda interface',
        ],
    ],

    'harmony' => [
        'excerpt' => 'Harmony Inspire’s website: collections with designers, a journal and a visual experience built around inspiration.',
        'role' => 'Front end, forms and multi-brand integration',
        'sections' => [
            [
                'title' => 'About the project',
                'body' => [
                    'Harmony Inspire is Peronda Group’s brand focused on design and inspiration. Its website presents ceramic collections with a more editorial approach: designers, settings and the journal take center stage, without losing the ability to look up products and download sales material.',
                    'The project is part of the group’s multi-site platform, but Harmony has its own front end, routes and experience. The catalog comes from Peronda Cloud; brand content (journal, about, designers) is managed in the CMS. Stack: Laravel 9, Livewire 2 and Vue 2, with media on S3, multi-language support and lead capture via Mailchimp/CRM.',
                ],
            ],
            [
                'title' => 'What the site includes',
                'body' => [
                    'Editorial home page with featured collections (collaborations with design studios). Collections / projects grid with filters by designer, look, material and format.',
                    'Collection page: the collection, settings, pieces, gallery, designer and contact. Designers section (listing + page with intro, video and related collections). The Journal: archive, single posts and tags.',
                    'Catalogs (grid + viewer), 3D resources, about, virtual stand, newsletter and contact forms (own brand → Mailchimp / CRM). Multi-language.',
                ],
            ],
            [
                'title' => 'Challenge and solution',
                'body' => [
                    'Balancing inspiration (images, designers, journal) with product usefulness (downloads, detail pages, contact). Navigation was modeled around collections and authors, with media-rich project pages.',
                    'Leads and newsletters are handled by the multi-brand contact module (Livewire, reCAPTCHA, asynchronous jobs).',
                ],
            ],
            [
                'title' => 'Tech stack',
                'body' => [
                    'PHP 8.2 · Laravel 9.52 · Livewire 2.12 · Vue 2.7 · Laravel Mix, Sass/Stylus. Media library / S3, i18n, SEO, sitemaps. Mailchimp (Harmony list), Redis queues, AWS. Catalog synced via import:harmony from Peronda Cloud.',
                ],
            ],
            [
                'title' => 'My contribution',
                'body' => [
                    'At Harmony I worked on the front end (collections, designers, journal), forms, newsletter and integration with the group’s multi-brand ecosystem. I worked with Laravel, Livewire and Vue: the brand’s own views and flows, product data from Peronda Cloud, and contact/newsletter connected to Mailchimp and the CRM.',
                    'Part of Peronda Group’s multi-brand platform (Laravel 9): one codebase, several domains, a catalog synced with Peronda Cloud and AWS infrastructure.',
                ],
            ],
        ],
        'image_alts' => [
            'Harmony Inspire home page',
            'Harmony Inspire screenshot',
            'Harmony Inspire detail',
        ],
    ],

    'museum' => [
        'excerpt' => 'Museum’s website: a premium brand, 4D technology, the Elevate line and digital experiences for trade fairs and showrooms.',
        'role' => 'Museum front end, Elevate and integrations',
        'sections' => [
            [
                'title' => 'About the project',
                'body' => [
                    'Museum Surfaces is Peronda Group’s premium brand. Its website showcases high-end surfaces and innovation (4D Tech, Elevate) and offers a catalog of collections for design and architecture professionals.',
                    'Beyond the product, the site includes digital experiences tied to trade fairs and showrooms (virtual stands, Cevisama/Cersaie/Coverings), a journal and contact/warranty flows. Technically it shares the group’s multi-tenant Laravel platform, with Museum-specific front end and modules, a catalog synced with Peronda Cloud, media on S3 and lead capture with Mailchimp and the CRM via queues.',
                ],
            ],
            [
                'title' => 'What the site includes',
                'body' => [
                    'Premium home page with new collections and brand storytelling. Collection catalog (filters by designer / look) and product page (settings, pieces, decors, downloads).',
                    'Elevate: a line of large slabs (countertops/wall cladding), 4D collections (Absolute, Infinity, Metal, Arabescato, Venatino…), Vue galleries, layouts and downloads. 4D Tech page, brand/about and an editorial journal.',
                    'Catalogs + viewer, virtual showroom, virtual stands (Cersaie, Cevisama, Coverings…), warranty form, contact and newsletter (Museum Mailchimp list). Multi-language.',
                ],
            ],
            [
                'title' => 'Challenge and solution',
                'body' => [
                    'Museum needs to sell a premium image while also explaining technologies (4D, 12 mm Elevate). That is why there are microsites/sections with their own layouts (Elevate), plenty of media and Vue components for galleries and pieces.',
                    'Trade fairs and virtual showrooms take the site beyond a static catalog. Product data comes from Peronda Cloud (import:museum); leads and CRM run in the background.',
                ],
            ],
            [
                'title' => 'Tech stack',
                'body' => [
                    'PHP 8.2 · Laravel 9.52 · Livewire 2.12 · Vue 2.7 · Laravel Mix, Sass/Stylus. i18n, SEO, Media/S3, Redis queues, Museum Mailchimp, reCAPTCHA, AWS. Peronda Cloud sync + special pages (Elevate, virtual stands, warranty).',
                ],
            ],
            [
                'title' => 'My contribution',
                'body' => [
                    'At Museum I worked on developing the front end (catalog, Elevate, virtual experiences), forms and integration with the group’s shared platform. I worked with Laravel, Livewire and Vue: the brand’s own views and flows, product data from Peronda Cloud, and contact/newsletter connected to Mailchimp and the CRM.',
                    'Part of Peronda Group’s multi-brand platform (Laravel 9): one codebase, several domains, a catalog synced with Peronda Cloud and AWS infrastructure.',
                ],
            ],
        ],
        'image_alts' => [
            'Museum Surfaces home page',
            'Museum Surfaces screenshot',
            'Museum Surfaces detail',
            'Museum Surfaces catalog',
            'Museum Surfaces interface',
        ],
    ],

    'apadrina-un-olivo' => [
        'excerpt' => 'Website and operating system for the Apadrina un Olivo project: adopting abandoned olive trees in Oliete, recurring payments, a sponsor area, a management panel and an API for field work.',
        'role' => 'Platform, payments and integrations',
        'sections' => [
            [
                'title' => 'About the project',
                'body' => [
                    'Apadrinaunolivo.org is the digital platform of the Apadrina un Olivo (Sponsor an Olive Tree) project, operating out of Oliete (Teruel, Spain): recovering abandoned olive trees, creating rural jobs and connecting sponsors with a tangible impact. The product’s goal is to turn a charitable cause into a stable system of adoptions and renewals: choose an olive tree, pay (subscription or one-off), manage gifts, receive olive oil/updates and download tax certificates.',
                    'Technically it is a Laravel 8 application with an interactive Livewire 2 UI, a Backpack admin panel and a modern front end with Alpine.js and Tailwind. Payments are handled mainly with Stripe via Laravel Cashier (webhooks, customers, payment methods, subscriptions), with legacy CECA support. The ecosystem is completed by Mailchimp, SparkPost transactional email, S3 storage, Redis queues, PDF generation (invoices/certificates), olive tree QR codes, Excel imports/exports and an API for field operations (plots, trees, PWA). Deployment relies on Docker in development and AWS (Elastic Beanstalk + CodePipeline) in staging/production.',
                ],
            ],
            [
                'title' => 'What the site includes',
                'body' => [
                    'Home page and project pages (economy, social, village, environment, olive oil, education, visits…). Multi-step adoption flow (plans, login/sign-up, shipping details, payment). Adoption as a gift (delivery, token redemption, recipient management). Renewals, card changes and Stripe payment confirmation. Coupons / promotions and an adoption landing page.',
                    'Sponsor area (Livewire): adoptions, gifts, news, tax details, certificates. Donation certificates and invoices as PDF. A QR code per olive tree and related forms. ES / EN / FR multi-language.',
                    'Admin panel (Backpack): users, adoptions, olive trees, plots, invoices, gifts, coupons, products, promotions, reporting and fix/sync tools. Versioned API (trees, plots, adoptions, users, Stripe/CECA webhooks, field PWA endpoints). Newsletter / Mailchimp sync.',
                ],
            ],
            [
                'title' => 'Challenge and solution',
                'body' => [
                    'The challenge is combining impact storytelling with a real subscription and logistics business (thousands of olive trees, gifts, renewals, invoicing and CRM). It is solved with Laravel + Cashier/Stripe (and legacy CECA), Redis jobs/queues, payment webhooks, Mailchimp sync and an operations admin built with Livewire for reporting, Excel imports/exports and data fixes.',
                    'The interactive front end (adoption and private area) lives in Livewire + Alpine/Tailwind; field work relies on a PWA API and images with GPS metadata.',
                ],
            ],
            [
                'title' => 'Tech stack',
                'body' => [
                    'PHP 7.3+/8.x · Laravel 8.83 · Livewire 2.10 · Laravel Cashier 13.16 · Stripe PHP · Backpack CRUD · Alpine.js 3 · Tailwind CSS 3 · Laravel Mix 6 · Sass · jQuery. Multi-language (mcamara/laravel-localization) · Spatie Translatable / Newsletter (Mailchimp).',
                    'Snappy/wkhtmltopdf · Maatwebsite Excel · Simple QRcode · Short URLs · Redis (Predis) · AWS S3 · CloudWatch · Elastic Beanstalk · CodePipeline · Docker · SparkPost · Invisible reCAPTCHA · wire-elements/modal · Imagick.',
                ],
            ],
            [
                'title' => 'My contribution',
                'body' => [
                    'At Apadrina un Olivo I worked on developing and maintaining the platform: adoption/payment/gift flows, the sponsor area, the operations admin, Stripe/Mailchimp integrations, the field API/PWA, invoicing/certificates and AWS deployment.',
                ],
            ],
        ],
        'image_alts' => [
            'Apadrina un Olivo home page',
            'Adoption flow',
            'Sponsor area and olive tree tracking',
            'Social and environmental impact in Oliete',
        ],
    ],

    'tiles-in-mind' => [
        'excerpt' => 'TIM’s online tile store: a filterable catalog, samples, a professional area with price lists and a multi-gateway checkout, connected to the Peronda/TIM ecosystem.',
        'role' => 'E-commerce, checkout and ERP integrations',
        'sections' => [
            [
                'title' => 'About the project',
                'body' => [
                    'Tilesinmind.com is the e-commerce store of Tiles in Mind, a tile brand aimed at both consumers and industry professionals. The goal was to sell ceramics online with the same data density as a technical catalog (formats, finishes, shapes, sizes…) while also working as a real ordering channel: samples, B2B price lists, quotes and sync with the TIM/Peronda ERP.',
                    'The platform was built on Laravel 9 and GetCandy, with a heavily Livewire-based front end for the catalog, cart and checkout, and a dual back office: Nova for content/CMS and GetCandy Admin Hub for order operations. Catalog, prices and stock are fed by CSV imports via Synology FTP; orders are exported back as CSV to Sent/Processed folders for the internal workflow. Several gateways coexist at checkout (Redsys, PayPal, Klarna, bank transfer) and logistics distinguish samples (Tipsa) from products (Dachser). The result is a production ceramics e-commerce store, not just a catalog: commerce + ops + ERP integration in a single digital product.',
                ],
            ],
            [
                'title' => 'What the site includes',
                'body' => [
                    'Catalog of collections and pieces with filters (shape, color, size, finish, material, look, room, use…). Product / collection pages, technical sheets, graphics and downloads.',
                    'Cart and checkout (consumer and professional), sample ordering and the Tipsa logistics flow. Professional area: orders, coupons, PDF quotes, projects / ideas. Wishlist, inspiration/blog and CMS pages (benefits, FAQs, shipping, financing…).',
                    'Payments: card (Redsys), PayPal, Klarna and bank transfer. Order hub (GetCandy Admin) + CMS (Laravel Nova). CSV export of orders to the TIM FTP (Sent/Processed) and checking/resending from the admin.',
                ],
            ],
            [
                'title' => 'Challenge and solution',
                'body' => [
                    'The catalog and prices change frequently; this is handled with scheduled imports (import:pricestock, import:procoll) from TIM CSV files. The order doesn’t end on the website: an operational CSV is generated and uploaded to Synology for the ERP.',
                    'The front end is Livewire-first (product pages, cart, checkout, filters) on top of GetCandy; order operations (invoices, carriers, CSV) live in the Admin Hub.',
                ],
            ],
            [
                'title' => 'Tech stack',
                'body' => [
                    'PHP 8+ · Laravel 9 · Livewire 2.10 · GetCandy (core + admin) · Laravel Nova 3 · Laravel Mix 6 · Tailwind 3 · Alpine 2 · Sass. Laravel Octane + RoadRunner, Redis / Predis, queues and scheduled jobs.',
                    'Maatwebsite Excel · DomPDF · Spatie Media Library / Translatable · Synology FTP (Flysystem) · AWS S3 · ActiveCampaign · Redsys · PayPal · Klarna · Dachser · Tipsa · Pinterest CSV feed · SEO (romanzipp/laravel-seo) · Log Viewer.',
                ],
            ],
            [
                'title' => 'My contribution',
                'body' => [
                    'At Tiles in Mind I worked on developing and maintaining the e-commerce store: catalog/filters, checkout and gateways, the professional area, the Admin Hub (orders, ERP CSV, carriers), TIM imports and integration jobs. I worked with Laravel, Livewire and GetCandy, connecting commerce, operations and ERP sync in a single digital product.',
                ],
            ],
        ],
        'image_alts' => [
            'Tiles in Mind home page',
            'Tiles in Mind catalog and filters',
            'TIM checkout and shopping experience',
        ],
    ],

];
