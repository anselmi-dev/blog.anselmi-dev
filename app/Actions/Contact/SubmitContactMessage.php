<?php

namespace App\Actions\Contact;

use App\Models\ContactMessage;

class SubmitContactMessage
{
    public function handle(string $name, string $email, string $message): ContactMessage
    {
        return ContactMessage::query()->create([
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ]);
    }
}
