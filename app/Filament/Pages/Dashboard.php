<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Widgets\PendingOverview;
use App\Models\ContactMessage;
use App\Models\PageView;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

/**
 * Inicio del panel: saludo, resumen del día y accesos rápidos.
 */
class Dashboard extends BaseDashboard
{
    public function getHeading(): string
    {
        $name = Str::of((string) auth()->user()?->name)->before(' ')->toString();

        return $name !== '' ? "Hola, {$name}" : 'Hola';
    }

    public function getSubheading(): string
    {
        $visits = PageView::query()->since(today())->count();
        $unread = ContactMessage::query()->whereNull('read_at')->count();
        $untranslated = array_sum(PendingOverview::untranslated());

        return collect([
            $visits === 1 ? '1 visita hoy' : "{$visits} visitas hoy",
            $unread === 1 ? '1 mensaje sin leer' : "{$unread} mensajes sin leer",
            $untranslated === 1 ? '1 texto sin traducir' : "{$untranslated} textos sin traducir",
        ])->implode(' · ').'.';
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('newPost')
                ->label('Escribir un post')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->url(PostResource::getUrl('create')),
            Action::make('viewSite')
                ->label('Ver el sitio')
                ->iconButton()
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url(route('home'), shouldOpenInNewTab: true),
        ];
    }
}
