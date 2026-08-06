<?php

namespace App\Livewire;

use App\Actions\Contact\SubmitContactMessage;
use App\Models\Setting;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

class ContactModal extends Component
{
    private const int CLOSE_MS = 400;

    public bool $show = false;

    public bool $leaving = false;

    public bool $success = false;

    public string $name = '';

    public string $email = '';

    public string $message = '';

    public function updatedShow(bool $value): void
    {
        if ($value) {
            $this->js('window.dispatchEvent(new CustomEvent("contact-modal-open"))');
        } else {
            $this->js('window.dispatchEvent(new CustomEvent("contact-modal-close"))');
        }
    }

    #[On('open-contact-modal')]
    public function open(): void
    {
        $this->success = false;
        $this->leaving = false;
        $this->resetValidation();
        $this->show = true;
    }

    public function close(): void
    {
        if (! $this->show || $this->leaving) {
            return;
        }

        $this->leaving = true;
        $this->js('setTimeout(() => $wire.finishClose(), '.self::CLOSE_MS.')');
    }

    public function finishClose(): void
    {
        if (! $this->leaving) {
            return;
        }

        $this->leaving = false;
        $this->success = false;
        $this->show = false;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingresá un correo válido.',
            'message.required' => 'Contanos un poco sobre el proyecto.',
            'message.min' => 'El mensaje debe tener al menos :min caracteres.',
        ];
    }

    public function submit(SubmitContactMessage $action): void
    {
        $this->validate();

        $key = 'contact:'.request()->ip();

        $executed = RateLimiter::attempt(
            $key,
            5,
            fn () => $action->handle($this->name, $this->email, $this->message),
            decaySeconds: 60,
        );

        if (! $executed) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Demasiados mensajes. Probá de nuevo en {$seconds} segundos.",
            ]);
        }

        $this->reset(['name', 'email', 'message']);
        $this->resetValidation();
        $this->success = true;
    }

    public function render()
    {
        $contact = Setting::contact();
        $site = Setting::site();

        return view('livewire.contact-modal', [
            'contact' => $contact,
            'contactEmail' => $contact['email'] ?: config('mail.from.address', 'hola@ejemplo.com'),
            'socialLinks' => $contact['social_links'],
            'siteName' => $site['author_name'] ?: config('app.name', 'Sitio'),
        ]);
    }
}
