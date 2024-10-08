<div class=" fixed  h-full  flex bg-white lg:shadow-sm overflow-hidden inset-0 lg:top-16  lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg">

    <div class="hidden lg:flex relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border-r" >
        <livewire:chat.chat-list :selectedConversation="$selectedConversation" :query="$query"/>
    </div>

    <div class="w-full h-full relative overflow-y-auto" style="contain:content">

        <livewire:chat.messages-box :selectedConversation="$selectedConversation"/>

    </div>

</div>
