<div class="max-w-[76rem] mx-auto my-10">

    <h5 class="text-center text-5xl font-bold py-3 text-[#f746c5]">Users</h5>



    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 p-2 ">

        @foreach ($users as $user)
            


        {{-- child --}}
        <div class="w-full bg-white border border-gray-100 rounded-lg py-8">

            <div class="flex flex-col items-center pb-2">

              <img src="https://randomuser.me/api/portraits/men/{{$user->id}}.jpg" alt="image" class="w-24 h-24 mb-2 rounded-full shadow-lg">


                <h5 class="mb-1 text-xl font-medium text-gray-900 " >
                    {{$user->name}}
                </h5>
                <span class="text-sm text-gray-500">{{$user->email}} </span>

                <div class="flex mt-4 space-x-3 md:mt-6">

                    {{-- <x-secondary-button>
                        Add Friend
                    </x-secondary-button> --}}

                    <x-primary-button wire:click="message({{$user->id}})" >
                        Message
                    </x-primary-button>

                </div>

            </div>


        </div>

        @endforeach
    </div>




</div>