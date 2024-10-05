<div
x-data="{
    height:0,
    conversationElement:document.getElementById('conversation'),
    markAsRead:null
}"
 x-init="
        height= conversationElement.scrollHeight;
        $nextTick(()=>conversationElement.scrollTop= height);


        {{-- Echo.private('users.{{Auth()->User()->id}}')
        .notification((notification)=>{
            if(notification['type']== 'App\\Notifications\\MessageRead' && notification['conversation_id']== {{$this->selectedConversation->id}})
            {

                markAsRead=true;
            }
        }); --}}
 "

 @scroll-bottom.window="
 $nextTick(()=>
 conversationElement.scrollTop= conversationElement.scrollHeight
 );
 "

class="w-full h-full flex flex-col overflow-hidden">

    <div class="border-b flex flex-col overflow-y-scroll grow h-full">


        {{-- header --}}
        <header class="w-full sticky inset-x-0 flex pb-[5px] pt-[5px] top-0 z-10 bg-white border-b ">

            <div class="flex w-full items-center px-2 lg:px-4 gap-2 md:gap-5">

                <a class="shrink-0 lg:hidden" href="#">


                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                    </svg>


                </a>


                {{-- avatar --}}


                <div class="shrink-0">
                    <x-avatar src="https://randomuser.me/api/portraits/men/{{ $selectedConversation->getReceiver()->id }}.jpg" class="h-9 w-9 lg:w-11 lg:h-11" />
                </div>


                <div>
                    <h6 class="font-bold truncate -mb-2"> {{ $selectedConversation->getReceiver()->name }}</h6>
                    <small class="text-gray-500">{{ $selectedConversation->getReceiver()->email }}</small>
                </div>


            </div>


        </header>

        {{-- Body --}}
        <main
            @scroll="
            scropTop = $el.scrollTop;
    
            if(scropTop <= 0){
    
            window.livewire.emit('loadMore');
    
            }"
            id="conversation"
            class="flex flex-col gap-3 p-2.5 overflow-y-auto  flex-grow overscroll-contain overflow-x-hidden w-full my-auto">

            @if ($loadedMessages)

            @php
                $previousMessage= null;
            @endphp
    
    
            @foreach ($loadedMessages as $key=> $message)
                
            {{-- keep track of the previous message --}}
          
            @if ($key>0)
    
            @php
                $previousMessage= $loadedMessages->get($key-1)
            @endphp
                
            @endif

            <div @class([
                'max-w-[85%] md:max-w-[78%] flex w-auto gap-2 relative mt-2',
                'ml-auto'=>$message->sender_id=== auth()->id(),
            ])>

                {{-- Avatar --}}
                <div @class([
                    'shrink-0', 
                    'invisible'=>$previousMessage?->sender_id==$message->sender_id,
                    'hidden'=>$message->sender_id === auth()->id()])>
                    <x-avatar src="https://randomuser.me/api/portraits/men/{{ $message->sender_id }}.jpg" />
                </div>

                {{-- messsage body --}}

                <div class="flex flex-wrap flex-col">



                    <p
                    @class([
                    'rounded-3xl py-2 px-5 text-gray-700 bg-[#f6f6f8fb] whitespace-normal truncate text-sm md:text-base tracking-wide lg:tracking-normal',
                    'rounded-bl-[3px] border  border-gray-200/40 ' =>!($message->sender_id=== auth()->id()),
                    'rounded-br-[3px] bg-gradient-to-tr from-[#FF61D2] to-[#FE9090] text-gray-100' => $message->sender_id=== auth()->id(),
                ])
                   >
                        {{$message->body}}
                    </p>

                    <div class="ml-auto flex gap-2 mt-2">

                        <p class='text-xs text-gray-500'>


                                {{$message->created_at->format('g:i a')}}

                        </p>

                            {{-- message status , only show if message belongs auth --}}

                            @if ($message->sender_id=== auth()->id())
                            
            
                        <div x-data="{markAsRead:@json($message->isRead())}">

                            {{-- double ticks --}}

                            <span x-cloak x-show="markAsRead" @class('text-gray-200')>
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="#4E65FF" class="bi bi-check2-all" viewBox="0 0 16 16">
                                    <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                    <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                </svg>
                            </span>

                            {{-- single ticks --}}
                            <span x-show="!markAsRead" @class('text-gray-200')>
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="#6b7280" class="bi bi-check2" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                            </span>
                        

                        </div>
                        
                        @endif

                    </div>
                    
                </div>
            </div>
                @endforeach
            @endif
        </main>

        {{-- send message  --}}

        <footer class="shrink-0 z-10 bg-white inset-x-0">

            <div class=" p-2 border-t">
    
                <form
                x-data="{ body: @entangle('body').defer }"
                @submit.prevent="$wire.sendMessage"
                method="POST" autocapitalize="off">
                    @csrf

                    <input type="hidden" autocomplete="false" style="display:none">

                    <div class="grid grid-cols-[26fr_1fr] items-center gap-5">
                        <input 
                            x-model="body"
                            type="text" 
                            autocomplete="off" 
                            autofocus 
                            placeholder="Message"
                            maxlength="1700"
                            class=" bg-gray-100 border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-full  focus:outline-none">

                        <button x-bind:disabled="!body.trim()" class="w-2" type='submit'><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#6b7280" class="bi bi-send text-center" viewBox="0 0 16 16">
                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
                          </svg></button>

                    </div>

                </form>

                <p> </p>
            </div>





        </footer>

    </div>
</div>
