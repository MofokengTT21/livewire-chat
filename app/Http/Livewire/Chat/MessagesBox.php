<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;

class MessagesBox extends Component
{   
    
    public $selectedConversation;

    public function render()
    {
        return view('livewire.chat.messages-box');
    }
}
