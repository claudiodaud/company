<div>
  <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Companies Index') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">                
              {{--Component Form--}}
               
                

                <form class="m-4 p-4" wire:submit.prevent="save">
                    @csrf

                    <div>
                        <x-jet-label for="name" value="{{ __('Name') }}" />
                        <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus wire:model="name"/>
                        <x-jet-validation-errors class="mt-4 ml-4 pt-4 pl-4" />
                    </div>                            

                    <div class="flex items-center justify-end mt-4">
                        
                        <x-jet-button class="ml-4">
                            {{ __('Register') }}
                        </x-jet-button>
                    </div>
                </form>


              {{--End Component Form--}}
          </div>
      </div>
    </div>
</div>

