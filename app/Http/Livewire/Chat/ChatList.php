<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;
    public $query;

    public function loadMore(): void
    {


        #increment 
        $this->paginate_var += 10;

        #call loadMessages()

        $this->loadMessages();


        #update the chat height 
        $this->dispatchBrowserEvent('update-chat-height');
    }


    

    public function loadMessages()
    {

        $userId = auth()->id();
        #get count
        $count = Message::where('conversation_id', $this->selectedConversation->id)
            ->where(function ($query) use ($userId) {

                $query->where('sender_id', $userId)
                    ->whereNull('sender_deleted_at');
            })->orWhere(function ($query) use ($userId) {

                $query->where('receiver_id', $userId)
                    ->whereNull('receiver_deleted_at');
            })
            ->count();

        #skip and query
        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
            ->where(function ($query) use ($userId) {

                $query->where('sender_id', $userId)
                    ->whereNull('sender_deleted_at');
            })->orWhere(function ($query) use ($userId) {

                $query->where('receiver_id', $userId)
                    ->whereNull('receiver_deleted_at');
            })
            ->skip($count - $this->paginate_var)
            ->take($this->paginate_var)
            ->get();


        return $this->loadedMessages;
    }

    protected $listeners=['refresh'=>'$refresh'];

    public function render()
    {
        $user = auth()->user();
        $conversations = $user->conversations(); // Fetches the conversations as an array or collection

        return view('livewire.chat.chat-list', compact('conversations'));
    }
}
