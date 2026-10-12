<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Font;
use App\Models\Post;
use App\Models\ReadingBook;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Traducción al inglés del contenido base (el que carga ContentSeeder).
 * Es idempotente: busca cada registro por su clave en español y solo completa
 * los campos "_en" que estén vacíos, así no pisa lo editado desde Filament.
 */
class EnglishContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->translatePosts();
        $this->translateFaqs();
        $this->translateTools();
        $this->translateFonts();
        $this->translateReadingBooks();
    }

    private function translatePosts(): void
    {
        $posts = [
            'nota-iterar' => [
                'kicker_en' => 'Mar 2025 · process',
                'title_en' => 'Iterating without drama',
                'excerpt_en' => 'Agreeing on limits per delivery: what’s in and what’s out, without promising the moon.',
                'body_en' => [
                    'When the product demands speed, technical debt shows up disguised as “we’ll fix it later”. I prefer to put the scope explicitly on the table: what goes into this delivery, what stays out and what gets reviewed the following week.',
                    'I write these notes for my future self and for whoever works on the same code: brief context, decisions and trade-offs. No manifestos.',
                    'If the team can move fast without feeling like it’s lying to the roadmap, we’ve already won half the game.',
                ],
            ],
            'nota-livewire' => [
                'kicker_en' => 'Feb 2025 · stack',
                'title_en' => 'Laravel and Livewire',
                'excerpt_en' => 'Forms, modals and wire:navigate: fewer layers if you respect conventions.',
                'body_en' => [
                    'It isn’t magic: it’s convention, good abstractions and fewer layers than it seems. Forms, modals and SPA-style navigation with wire:navigate save me endless decision meetings on small and medium projects.',
                    'When something grows, I separate domain, policies and views. The framework already gives you the thread; the rest is discipline and code reviews that don’t hurt.',
                ],
            ],
            'foto-bento-r' => [
                'kicker_en' => 'Photography · reference',
                'title_en' => 'Side light on a façade',
                'excerpt_en' => 'A shot used in the blog bento: stone / wood contrast.',
                'caption_en' => 'Test image for the bento layout (Picsum). In production, replace it with your own file or CDN.',
                'alt_en' => 'Façade in natural light',
                'body_en' => [
                    'I like alternating text blocks with photos so the reading can breathe. The same piece can live as a thumbnail in the grid and with more context here.',
                ],
            ],
            'foto-bento-l' => [
                'kicker_en' => 'Photography · reference',
                'title_en' => 'Vertical framing',
                'excerpt_en' => 'Second vertical piece in the bento.',
                'caption_en' => 'Another visual reference for the grid; useful for testing heights and crops.',
                'alt_en' => 'Scene in portrait format',
            ],
            'foto-bento-m' => [
                'kicker_en' => 'Photography · reference',
                'title_en' => 'Square detail',
                'excerpt_en' => 'An almost square cell in the bento.',
                'caption_en' => 'Ideal for details, textures or a single strong idea in the center.',
                'alt_en' => 'Photographic detail',
                'body_en' => [
                    'On mobile this image stacks with the rest; on desktop it keeps a square ratio within the grid.',
                ],
            ],
            'nota-foto' => [
                'kicker_en' => 'Jan 2025 · craft',
                'title_en' => 'Code and photography',
                'excerpt_en' => 'Two ways of framing: composition and margins in UI. What you leave out counts just as much.',
                'body_en' => [
                    'Both teach you to look at the margin: what you leave out matters as much as what you show.',
                    'On this site I mix editorial blocks with a gallery and games — the same sense of rhythm and white space.',
                ],
            ],
            'nota-docs' => [
                'kicker_en' => 'Dec 2024 · team',
                'title_en' => 'Document the bare minimum',
                'excerpt_en' => 'A short README, decisions in PRs and one place for links. That alone saves the next person.',
                'body_en' => [
                    'You don’t need a hundred-page manual on day one. A README that says how to run the project, where the env files are and a link to the board or channel already reduces friction.',
                    'Important decisions can live in the PR description or in short ADRs when the trade-off is worth it.',
                ],
            ],
            'nota-no' => [
                'kicker_en' => 'Nov 2024 · product',
                'title_en' => 'Saying no with data',
                'excerpt_en' => 'When scope grows, show the cost in time and risk before nodding along.',
                'body_en' => [
                    'A quick “yes” without an estimate usually turns into free work and frustration. I prefer to translate the request into days or hours and point out what stops getting done if this comes in.',
                    'It isn’t about blocking: it’s about aligning expectations with something tangible on the table.',
                ],
            ],
            'nota-tests' => [
                'kicker_en' => 'Oct 2024 · quality',
                'title_en' => 'Tests that matter',
                'excerpt_en' => 'The happy path + two odd edge cases are usually enough at first; the rest when it really hurts.',
                'body_en' => [
                    'Covering the happy path and a couple of edge cases that have already bitten you pays off more than chasing 100% coverage on screens that change every sprint.',
                    'When a bug comes back, that’s when I add the missing test: the regression gets documented in code.',
                ],
            ],
        ];

        foreach ($posts as $slug => $translation) {
            $this->fill(Post::query()->where('slug', $slug), $translation);
        }
    }

    private function translateFaqs(): void
    {
        $faqs = [
            '¿Con qué stack trabajás?' => [
                'question_en' => 'What stack do you work with?',
                'answer_en' => 'Mainly Laravel, Livewire and Tailwind on the front end, with modern build tools (Vite). For APIs or specific pieces, I choose whatever fits the project best.',
            ],
            '¿Hacés solo desarrollo o también diseño?' => [
                'question_en' => 'Do you only do development, or design too?',
                'answer_en' => 'I can take you from a clear visual foundation to production-ready code. If you already have a design in Figma or another tool, I handle the build and integration without friction.',
            ],
            '¿Cómo es el proceso de un proyecto típico?' => [
                'question_en' => 'What does a typical project process look like?',
                'answer_en' => 'We start by defining scope and priorities, then iterate with visible deliveries (staging or previews). At the end we leave a documented deployment and room for reasonable post-launch adjustments.',
            ],
            '¿Trabajás por hora o por proyecto?' => [
                'question_en' => 'Do you work by the hour or per project?',
                'answer_en' => 'It depends on the type of work. Fixed scopes usually go with a fixed quote; ongoing support or consulting can be hourly. We make it explicit in writing from the first contact.',
            ],
            '¿Podés mantener o mejorar un sitio que ya existe?' => [
                'question_en' => 'Can you maintain or improve an existing site?',
                'answer_en' => 'Yes: code audits, dependency upgrades, performance, accessibility and new features on Laravel or other codebases, always weighing cost against benefit.',
            ],
            '¿Qué necesitás para cotizar?' => [
                'question_en' => 'What do you need to give a quote?',
                'answer_en' => 'A short description of the goal, rough timelines and, if you have them, visual or technical references. With that I’ll suggest a next step: a short call or a written proposal.',
            ],
            '¿Ofrecés hosting o dominios?' => [
                'question_en' => 'Do you offer hosting or domains?',
                'answer_en' => 'I don’t sell hosting as a product; I recommend providers depending on the case and can help you deploy and leave the environment documented so you stay in control.',
            ],
            '¿Cómo te contacto?' => [
                'question_en' => 'How do I get in touch?',
                'answer_en' => 'From any page you can open the contact modal (the “Contact” button or the footer shortcut). Your message arrives there and we reply as soon as possible.',
            ],
        ];

        foreach ($faqs as $question => $translation) {
            $this->fill(Faq::query()->where('question', $question), $translation);
        }
    }

    private function translateTools(): void
    {
        $tools = [
            'Laravel' => ['tagline_en' => 'Backend & app structure'],
            'Livewire' => ['tagline_en' => 'Dynamic UI without a heavy SPA'],
            'Tailwind CSS' => ['tagline_en' => 'Utility-first styling'],
            'Alpine.js' => ['tagline_en' => 'Lightweight client-side interactions'],
            'Vite' => ['tagline_en' => 'Builds and hot reload'],
            'GSAP' => ['tagline_en' => 'Animation and motion'],
            'MySQL' => ['tagline_en' => 'Relational database'],
            'AWS' => ['tagline_en' => 'Infrastructure and deployment'],
        ];

        foreach ($tools as $name => $translation) {
            $this->fill(Tool::query()->where('name', $name), $translation);
        }
    }

    private function translateFonts(): void
    {
        $fonts = [
            'plus-jakarta-sans' => ['note_en' => 'The site’s main typeface (layout).'],
            'playfair-display' => ['note_en' => 'Elegant for editorial headlines.'],
            'space-grotesk' => ['note_en' => 'Geometric, ideal for modern UI.'],
            'jetbrains-mono' => ['note_en' => 'For code and technical labels.'],
            'fraunces' => ['category_en' => 'Variable serif', 'note_en' => 'An expressive serif with a “soft” character.'],
        ];

        foreach ($fonts as $slug => $translation) {
            $this->fill(Font::query()->where('slug', $slug), $translation);
        }
    }

    private function translateReadingBooks(): void
    {
        $books = [
            'Manual de Estoicismo' => ['title_en' => 'The Enchiridion', 'author_en' => 'EPICTETUS'],
            'Meditaciones' => ['title_en' => 'Meditations', 'author_en' => 'MARCUS AURELIUS'],
            'El arte de la guerra' => ['title_en' => 'The Art of War'],
        ];

        foreach ($books as $title => $translation) {
            $this->fill(ReadingBook::query()->where('title', $title), $translation);
        }
    }

    /**
     * @param  Builder<Model>  $query
     * @param  array<string, mixed>  $translation
     */
    private function fill(Builder $query, array $translation): void
    {
        $query->each(function (Model $record) use ($translation): void {
            foreach ($translation as $attribute => $value) {
                if (blank($record->getAttribute($attribute))) {
                    $record->setAttribute($attribute, $value);
                }
            }

            $record->save();
        });
    }
}
