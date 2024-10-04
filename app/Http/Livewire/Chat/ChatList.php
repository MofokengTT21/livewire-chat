<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;

    public function render()
    {
        $user = auth()->user();
        $conversations = $user->conversations(); // Fetches the conversations as an array or collection

        return view('livewire.chat.chat-list', compact('conversations'));
    }
}
