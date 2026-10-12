<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;

/**
 * Descripción larga (español e inglés) y colores del frasco de las herramientas
 * base de /servicios/herramientas. Es idempotente: busca cada herramienta por
 * nombre y solo completa los campos vacíos.
 */
class ToolDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $descriptions = [
            'Laravel' => [
                'label_color' => '#b8432f',
                'fill_color' => '#e0a65c',
                'description' => 'Framework PHP para construir aplicaciones web completas: rutas, base de datos con Eloquent, colas, mails, autenticación y tests. Es la base de casi todo lo que hago; me deja enfocarme en el producto en lugar de reinventar la infraestructura.',
                'description_en' => 'A PHP framework for building complete web applications: routing, databases with Eloquent, queues, mail, authentication and testing. It’s the foundation of almost everything I build; it lets me focus on the product instead of reinventing the infrastructure.',
            ],
            'Livewire' => [
                'label_color' => '#c4678a',
                'fill_color' => '#e6bd84',
                'description' => 'Permite crear interfaces dinámicas escribiendo PHP y Blade, sin armar una SPA aparte. Lo uso para formularios, filtros, modales y paneles que se actualizan al instante, manteniendo la lógica en el servidor.',
                'description_en' => 'Lets you build dynamic interfaces by writing PHP and Blade, without a separate SPA. I use it for forms, filters, modals and dashboards that update instantly while keeping the logic on the server.',
            ],
            'Tailwind CSS' => [
                'label_color' => '#2a7487',
                'fill_color' => '#b7cd86',
                'description' => 'Framework de CSS basado en clases utilitarias. Diseño directamente en el HTML, con un sistema consistente de espacios, colores y tipografías, y sin pelearme con hojas de estilo que crecen sin control.',
                'description_en' => 'A utility-first CSS framework. I design directly in the HTML with a consistent system of spacing, colors and typography, without fighting stylesheets that grow out of control.',
            ],
            'Alpine.js' => [
                'label_color' => '#86bccb',
                'fill_color' => '#c99a4b',
                'description' => 'JavaScript liviano para sumar interacción directamente en el HTML: menús, pestañas, desplegables y pequeños estados de la interfaz. Complementa a Livewire para todo lo que no necesita ir al servidor.',
                'description_en' => 'Lightweight JavaScript for adding interactivity right in the HTML: menus, tabs, dropdowns and small bits of UI state. It complements Livewire for everything that doesn’t need a trip to the server.',
            ],
            'Vite' => [
                'label_color' => '#6a4fae',
                'fill_color' => '#e9c35b',
                'description' => 'Herramienta de build para el frontend: compila CSS y JavaScript, recarga los cambios al instante mientras desarrollo y genera archivos optimizados para producción.',
                'description_en' => 'A frontend build tool: it compiles CSS and JavaScript, reloads changes instantly while I develop and outputs optimized files for production.',
            ],
            'GSAP' => [
                'label_color' => '#7f9c2c',
                'fill_color' => '#d29a54',
                'description' => 'Librería de animación para la web. La uso para transiciones, revelados al hacer scroll y microinteracciones que le dan ritmo a un sitio sin sacrificar rendimiento.',
                'description_en' => 'An animation library for the web. I use it for transitions, scroll reveals and micro-interactions that give a site rhythm without sacrificing performance.',
            ],
            'MySQL' => [
                'label_color' => '#1e5876',
                'fill_color' => '#e0a64a',
                'description' => 'Base de datos relacional donde viven los datos de las aplicaciones: usuarios, pedidos, contenidos. Diseño las tablas, índices y consultas pensando en que respondan rápido cuando el proyecto crece.',
                'description_en' => 'The relational database where application data lives: users, orders, content. I design tables, indexes and queries so they stay fast as the project grows.',
            ],
            'AWS' => [
                'label_color' => '#2e3847',
                'fill_color' => '#f0a43a',
                'description' => 'Servicios de Amazon para la infraestructura: servidores, almacenamiento de archivos en S3, colas, mails y despliegues automatizados. Lo uso para que los proyectos estén disponibles, seguros y puedan escalar.',
                'description_en' => 'Amazon’s infrastructure services: servers, file storage on S3, queues, email and automated deployments. I use it to keep projects available, secure and ready to scale.',
            ],
        ];

        foreach ($descriptions as $name => $values) {
            Tool::query()->where('name', $name)->each(function (Tool $tool) use ($values): void {
                foreach ($values as $attribute => $value) {
                    if (blank($tool->getAttribute($attribute))) {
                        $tool->setAttribute($attribute, $value);
                    }
                }

                $tool->save();
            });
        }
    }
}
