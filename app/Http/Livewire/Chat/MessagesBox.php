<?php

namespace App\Http\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;

class MessagesBox extends Component
{   
    
    public $selectedConversation;
    public $body;
    public $loadedMessages;

    public function loadMessages()
    {
        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)->get();
        // ->where(function ($query) use ($userId) {

        //     $query->where('sender_id', $userId)
        //         ->whereNull('sender_deleted_at');
        // })->orWhere(function ($query) use ($userId) {

        //     $query->where('receiver_id', $userId)
        //         ->whereNull('receiver_deleted_at');
        // })
        // ->skip($count - $this->paginate_var)
        // ->take($this->paginate_var)
        // ->get();


    return $this->loadedMessages;
    }

    public function sendMessage()
    {
        $this->validate(['body'=>'required|string']);

        $createdMessage= Message::create([
            'conversation_id'=>$this->selectedConversation->id,
            'sender_id'=>auth()->id(),
            'receiver_id'=>$this->selectedConversation->getReceiver()->id,
            'body'=>$this->body
        ]);

        $this->reset('body');

        // scroll to the bottom

        $this->dispatchBrowserEvent('scroll-bottom');

        // Push the message
        $this->loadedMessages->push($createdMessage);

        // update the conversation model
        $this->selectedConversation->updated_at= now();
        $this->selectedConversation-> save();

        // refresh chatList
        $this->emitTo('chat.chat-list', 'refresh');

    }

    public function mount()
    {
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chat.messages-box');
    }
}
