<?php

namespace App\Livewire;

use App\Models\ContactMessage;
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
            $this->js('document.body.classList.add("overflow-hidden")');
        } else {
            $this->js('document.body.classList.remove("overflow-hidden")');
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

    public function submit(): void
    {
        $this->validate();

        ContactMessage::query()->create([
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ]);

        $this->reset(['name', 'email', 'message']);
        $this->resetValidation();
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.contact-modal');
    }
}
