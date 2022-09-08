<div>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      <span class="text-gray-400 uppercase">{{ __(App\Models\Company::find($companyId)->name.' /') }}</span> 
      <span class="text-gray-700">{{__(' Contracts Index')}}</span>
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">                
        

          {{--Component Table--}}

          <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="p-4 flex justify-between">
              <label class="sr-only">{{ __('Search') }}</label>
              <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="flex justify-start">
                <input type="text" id="table-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items" wire:model="search">

                 @if($active == true)
                    <a wire:click.prevent="active(false)" type='button' class='inline-flex items-center ml-6 px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest shadow-sm hover:text-red-500 hover:bg-red-50 focus:outline-none focus:border-gary-300 focus:ring focus:ring-blue-200 active:text-red-800 active:bg-gray-50 disabled:opacity-25 transition'>
                        {{ __('Deleted Registers') }}
                    </a>
                  @elseif($active == false)
                    <a wire:click.prevent="active(true)" type='button' class='inline-flex items-center ml-6 px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-green-700 uppercase tracking-widest shadow-sm hover:text-green-500 hover:bg-green-50 focus:outline-none focus:border-gray-300 focus:ring focus:ring-blue-200 active:text-green-800 active:bg-gray-50 disabled:opacity-25 transition'>
                        {{ __('Actives Registers') }}
                    </a>
                  @endif
                </div>

              </div>
              <div class="pt-2">  
                <a wire:click="$toggle('createNewContract')" type='button' class='inline-flex items-center bg-black px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:text-gray-200 hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition'>
                    {{ __('Create New') }}
                </a>

                <a wire:click="downloadContracts" type='button' class='inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 hover:bg-gray-200 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition'>
                    {{ __('Download') }}
                </a>
                <a href="{{ route('companies.index') }}" type='button' class='inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 hover:bg-gray-200 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition'>
                    {{ __('Return to company') }}
                </a>
              </div>
            </div>
            <div class="mx-4">

              {{--Flash Messages--}}
              <x-jet-action-message class="" on="deleted">
                <div class="text-xl font-normal  max-w-full flex-initial bg-red-100 p-4 my-4 rounded-lg border border-red-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-red-800 ">{{ __('Contract register successfull deleted') }}</div>  
                </div>        
              </x-jet-action-message>  

              <x-jet-action-message class="" on="forceDeleted">
                <div class="text-xl font-normal  max-w-full flex-initial bg-fuchsia-100 p-4 my-4 rounded-lg border border-fuchsia-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-fuchsia-900 ">{{ __('Contract register successfull force deleted') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="restore">
                <div class="text-xl font-normal  max-w-full flex-initial bg-blue-100 p-4 my-4 rounded-lg border border-blue-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-blue-900 ">{{ __('Contract register successfull restored') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="created">
                <div class="text-xl font-normal  max-w-full flex-initial bg-green-100 p-4 my-4 rounded-lg border border-green-800 ">
                  <div class="text-sm font-base px-4 text-green-800 ">{{ __('Contract register successfull created') }}</div>  
                </div>        
              </x-jet-action-message>  

              <x-jet-action-message class="" on="updated">
                <div class="text-xl font-normal  max-w-full flex-initial bg-indigo-100 p-4 my-4 rounded-lg border border-indigo-800 ">
                  <div class="text-sm font-base px-4 text-indigo-800 ">{{ __('Contract register successfull update') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="withoutUsers">
                <div class="text-xl font-normal  max-w-full flex-initial bg-indigo-100 p-4 my-4 rounded-lg border border-indigo-800 ">
                  <div class="text-sm font-base px-4 text-indigo-800 ">{{ __('The company has no users available') }}</div>  
                </div>        
              </x-jet-action-message> 

              {{--Table--}}
              <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 ">
                  <tr>
                    <th scope="col" class="px-6 py-3 rounded-tl-lg rounded-bl-lg">
                      {{ __('Id')}}
                    </th>
                    <th scope="col" class="px-6 py-3">
                      {{ __('Name')}}
                    </th>
                    <th scope="col" class="px-6 py-3">
                      {{ __('Users')}}
                    </th>
                    
                    <th scope="col" class="px-6 py-3 rounded-tr-lg rounded-br-lg text-right">
                      {{__('Actions')}}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($contracts as $contract)
                    <tr class="bg-white border-b hover:bg-gray-100 even:bg-gray-50">
                    <td class="px-6 py-4 w-10">
                      #{{$contract->id}}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap truncate ... ">
                      {{$contract->name}}
                    </th>
                    <td class="px-6 py-4 w-30">
                                            
                        <a wire:click="addRemoveContract({{$contract->id}} , {{$contract->company_id}})" href="#{{--route('users.index.company', $contract->company->id)--}}" 
                           type='button' 
                           class='font-medium bg-gray-300 text-white rounded-md px-2 hover:bg-gray-500 px-2 py-1'>
                          {{$contract->users->count()}} {{ __('Users') }}
                        </a>

                    </td>
                    
                    @if($active == true)
                      <td class="px-6 py-4 text-right w-80">
                        <a href="#" class="font-medium bg-indigo-300 text-white rounded-md px-2 hover:bg-indigo-500 px-2 py-1" 
                            wire:click="showContract({{$contract->id}})" wire:loading.attr="disabled">{{__('Detail')}}</a>
                        
                        <a  href="#" 
                            class="font-medium bg-blue-300 text-white rounded-md px-2 hover:bg-blue-500 px-2 py-1" 
                            wire:click="editContract({{$contract->id}})" wire:loading.attr="disabled">{{__('Edit')}}</a>
                        
                        <a  href="#" 
                            class="font-medium bg-red-300 text-white rounded-md px-2 hover:bg-red-500 px-2 py-1"
                            wire:click="confirmContractDeletion({{$contract->id}})" wire:loading.attr="disabled">{{__('Delete')}}</a>
                      </td>
                    @else
                      <td class="px-6 py-4 text-right w-80">
                                            
                        <a  href="#" 
                            class="font-medium bg-blue-300 text-white rounded-md px-2 hover:bg-blue-500 px-2 py-1" 
                            wire:click="confirmRestoreContract({{$contract->id}})" wire:loading.attr="disabled">{{__('Restore')}}</a>
                        
                        <a  href="#" 
                            class="font-medium bg-red-300 text-white rounded-md px-2 hover:bg-red-500 px-2 py-1"
                            wire:click="confirmForceContractDeletion({{$contract->id}})" wire:loading.attr="disabled">{{__('Force Delete')}}
                        </a>

                      </td>
                    @endif
                  </tr>
                  @empty
                    {{-- empty expr --}}
                  @endforelse
                  
                </tbody>
              </table>

            </div>
            {{--Pagination--}}
            <div class="p-4">
            {{$contracts->links()}}
            </div>
          </div>

          {{--End Component Table--}}
        
      </div>
    </div>
  </div>


 

<!-- Delete contract Modal -->
<x-jet-dialog-modal wire:model="deleteContract">
    <x-slot name="title">
        {{ __('Delete Contract') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to delete this contract? Once your contract account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete this contract account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-contract.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="passwordUser"
                        wire:keydown.enter="deleteContract" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('deleteContract')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="deleteContract" wire:loading.attr="disabled">
            {{ __('Delete Contract Account') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>

<!-- Force Delete Contract Modal -->
<x-jet-dialog-modal wire:model="forceDeleteContract">
    <x-slot name="title">
        {{ __('Force Delete Contract') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to force delete this contract? Once your contract account is force deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently contract your company account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-contract.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="password"
                        wire:keydown.enter="forceDeleteContract" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('forceDeleteContract')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="forceDeleteContract" wire:loading.attr="disabled">
            {{ __('Delete Contract') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>

<!-- restore Contract Modal -->
<x-jet-dialog-modal wire:model="restoreContract">
    <x-slot name="title">
        {{ __('Restore Contract') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to restore this contract? Once your contract account is restore, all of its resources and data will be permanently restore. Please enter your password to confirm you would like to permanently restore your contract account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-contract.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="password"
                        wire:keydown.enter="restoreContract" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('restoreContract')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="restoreContract" wire:loading.attr="disabled">
            {{ __('Restore Contract Account') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>


<!-- Create New Contract Modal -->
  <x-jet-dialog-modal wire:model="createNewContract"> 
      <x-slot name="title">
          {{ __('Create New Contract') }}
      </x-slot>

      <x-slot name="content">        


            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" wire:model="name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>
            
         
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="$toggle('createNewContract')" wire:loading.attr="disabled">
              {{ __('Cancel') }}
          </x-jet-secondary-button>

          <x-jet-danger-button class="ml-3" wire:click="saveContract" wire:loading.attr="disabled">
              {{ __('Create Contract Account') }}
          </x-jet-danger-button>
      </x-slot>
  </x-jet-dialog-modal>


  <!-- Edit Contract Modal -->
  <x-jet-dialog-modal wire:model="editContract"> 
      <x-slot name="title">
          {{ __('Update Contract Account Data') }}
      </x-slot>

      <x-slot name="content">
          
            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" wire:model="name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>

                                 
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="$toggle('editContract')" wire:loading.attr="disabled">
              {{ __('Cancel') }}
          </x-jet-secondary-button>

          <x-jet-danger-button class="ml-3" wire:click="updateContract" wire:loading.attr="disabled">
              {{ __('Update Contract Data') }}
          </x-jet-danger-button>
      </x-slot>
  </x-jet-dialog-modal>


  <!-- Show Contract Modal -->
  <x-jet-dialog-modal wire:model="showContract"> 
      <x-slot name="title">
          {{ __('Show Contract Data') }}
      </x-slot>

      <x-slot name="content">
          
        <div class="col-span-6 sm:col-span-4">
            @if($contractShow)
              <!-- Start: Invoice -->
                <div class="w-full">  
                  <div class="flex justify-between">
                    <div class="text-xs text-gray-400">{{__('Register')}} #{{$contractShow->id}}</div>
                    <div class="text-xs text-gray-400">{{__('Created at')}}: {{$contractShow->created_at}}</div>

                  </div>            
                  
                  <hr>
                  <div class="w-full flex justify-between mt-10">                   
                    <div class="text-sm text-gray-400">{{__('Name')}}:</div>                          
                    <div class="text-sm text-gray-600 uppercase">{{$contractShow->name}}</div>                            
                  </div> 
                  
                                    
                </div>              
              <!-- END: Invoice -->
            @endif

        </div>        
         
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="closeShowContract()" wire:loading.attr="disabled">
              {{ __('Return') }}
          </x-jet-secondary-button>      
      </x-slot>
  </x-jet-dialog-modal>

  <!-- Add / Remove Contract Modal -->
  <x-jet-dialog-modal wire:model="addRemoveContract" maxWidth="xl"> 
      <x-slot name="title">
          {{ __('Add or remove users to contract') }}
      </x-slot>

      <x-slot name="content">
          
        <div class="col-span-6 sm:col-span-4">
            @if($usersAddByContract)
              <!-- Start: Invoice -->
                <div class="w-full">  
                            
                    @foreach($usersAddByContract as $contract)
                      @foreach($contract->users as $user)

                        <hr>
                        <div class="w-full flex justify-between mt-10">                   
                          <div class="text-sm text-gray-400">
                            <span class="uppercase"><strong>{{$user->name}}</span> - </strong>{{$user->email}}</div>                          
                          <div class="text-sm text-gray-600 uppercase">
                            <x-jet-danger-button class="mb-4" wire:click="removeUserToContract({{$user->id}},{{$usersAddByContract[0]->id}},{{$usersAddByContract[0]->company_id}})" wire:loading.attr="disabled">
                                {{ __('Remove') }}
                            </x-jet-danger-button>
                          </div>                            
                        </div> 
                      @endforeach
                    @endforeach
                  
                  
                    @foreach($usersAddByCompany as $user)
                      
                        <hr>
                        <div class="w-full flex justify-between mt-10">                   
                          <div class="text-sm text-gray-400">
                            <span class="uppercase"><strong>{{$user->name}}</span> - </strong>{{$user->email}}</div>                          
                          <div class="text-sm text-gray-600 uppercase"> 
                            <x-jet-secondary-button 
                            wire:click="addUserToContract({{$user->id}},{{$usersAddByContract[0]->id}},{{$usersAddByContract[0]->company_id}})"
                            wire:loading.attr="disabled">
                                {{ __('Add') }}
                            </x-jet-secondary-button> 
                          </div>                            
                        </div> 
                      
                    @endforeach
                                  
                </div>              
              <!-- END: Invoice -->
            @endif
            
        </div>        
         
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="closeAddRemoveContract()" wire:loading.attr="disabled">
              {{ __('Return') }}
          </x-jet-secondary-button>      
      </x-slot>
  </x-jet-dialog-modal>   

</div>
