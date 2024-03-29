<div>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      <span class="text-gray-400 uppercase">{{ __(App\Models\Company::find($companyId)->name.' /') }}</span> 
      <span class="text-gray-700">{{__(' Customers Index')}}</span>
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">                
        

          {{--Component Table--}}

          <div class="shadow-md sm:rounded-lg">
             <div class="p-4 ">
              <div class="block sm:flex sm:justify-between">
                <div class="block sm:flex justify-start ">
                  <div class="relative">
                  @if(in_array("customer.filter", $permissions))
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                  @endif   
                  
                  @if(in_array("customer.filter", $permissions))
                      <input type="text" id="table-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block pl-10 py-3  sm:mx-0 sm:mr-2  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 w-full sm:w-60" placeholder="Search for items" wire:model="search">
                  @endif
                </div>
                    
                @if(in_array("customer.deleted", $permissions))    
                  @if($active == true)
                    <a wire:click.prevent="active(false)" type='button' class='inline-flex items-center  px-2 sm:px-2 py-3 sm:mx-2 sm:py-0 mt-2 sm:mt-0 bg-white border border-gray-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest shadow-sm hover:text-red-500 hover:bg-red-50 focus:outline-none focus:border-gary-300 focus:ring focus:ring-blue-200 active:text-red-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-60'>
                        {{ __('Deleted Registers') }}
                    </a>
                  @elseif($active == false)
                    <a wire:click.prevent="active(true)" type='button' class='inline-flex items-center px-2 sm:px-2 py-3 sm:mx-2 sm:py-0 mt-2 sm:mt-0 bg-white border border-gray-300 rounded-md font-semibold text-xs text-green-700 uppercase tracking-widest shadow-sm hover:text-green-500 hover:bg-green-50 focus:outline-none focus:border-gray-300 focus:ring focus:ring-blue-200 active:text-green-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-60'>
                        {{ __('Actives Registers') }}
                    </a>
                  @endif
                @endif 
                </div> 
              
              
                <div class="flex sm:justify-end">
                  @if(in_array("customer.create", $permissions))
                    <a wire:click="$toggle('createNewCustomer')" type='button' class='inline-flex items-center bg-black px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:text-gray-200 hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-40 py-3 sm:py-0 mt-2 sm:mt-0 sm:mx-2 mr-1'>
                        {{ __('Create New') }}
                    </a>
                  @endif
                  @if(in_array("customer.download", $permissions))
                    <a wire:click="downloadCustomers" type='button' class='inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 hover:bg-gray-200 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-40 py-3 sm:py-0 mt-2 sm:mt-0 sm:mx-2 ml-1 mr-1'>
                        {{ __('Download') }}
                    </a>
                  @endif
                    <a href="{{ route('companies.index') }}" type='button' class='inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 hover:bg-gray-200 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-40 py-3 sm:py-0 mt-2 sm:mt-0 sm:ml-2 ml-1'>
                    {{ __('Return to company') }}
                    </a>
                </div>
              </div>
            </div>
            <div class="mx-4">

              {{--Flash Messages--}}
              <x-jet-action-message class="" on="deleted">
                <div class="text-xl font-normal  max-w-full flex-initial bg-red-100 p-4 my-4 rounded-lg border border-red-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-red-800 ">{{ __('Customer register successfull deleted') }}</div>  
                </div>        
              </x-jet-action-message>  

              <x-jet-action-message class="" on="forceDeleted">
                <div class="text-xl font-normal  max-w-full flex-initial bg-fuchsia-100 p-4 my-4 rounded-lg border border-fuchsia-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-fuchsia-900 ">{{ __('Customer register successfull force deleted') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="restore">
                <div class="text-xl font-normal  max-w-full flex-initial bg-blue-100 p-4 my-4 rounded-lg border border-blue-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-blue-900 ">{{ __('Customer register successfull restored') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="created">
                <div class="text-xl font-normal  max-w-full flex-initial bg-green-100 p-4 my-4 rounded-lg border border-green-800 ">
                  <div class="text-sm font-base px-4 text-green-800 ">{{ __('Customer register successfull created') }}</div>  
                </div>        
              </x-jet-action-message>  

              <x-jet-action-message class="" on="updated">
                <div class="text-xl font-normal  max-w-full flex-initial bg-indigo-100 p-4 my-4 rounded-lg border border-indigo-800 ">
                  <div class="text-sm font-base px-4 text-indigo-800 ">{{ __('Customer register successfull update') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="withoutUsers">
                <div class="text-xl font-normal  max-w-full flex-initial bg-indigo-100 p-4 my-4 rounded-lg border border-indigo-800 ">
                  <div class="text-sm font-base px-4 text-indigo-800 ">{{ __('The customer has no users available') }}</div>  
                </div>        
              </x-jet-action-message> 

              {{--Table--}}
              <div class="relative overflow-x-auto">
              <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 ">
                  <tr>
                    <th scope="col" class="px-6 py-3 w-max rounded-tl-lg rounded-bl-lg">
                      {{ __('Id')}}
                    </th>
                    <th scope="col" class="px-6 py-3 w-max">
                      {{ __('Name')}}
                    </th>     
                    @if(in_array("viewContracts", $permissions))
                      <th scope="col" class="px-6 py-3 w-max">
                        {{ __('Contracts')}}
                      </th>
                    @endif                               
                    <th scope="col" class="px-6 py-3 w-max rounded-tr-lg rounded-br-lg text-right">
                      {{__('Actions')}}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($customers as $customer)
                    <tr class="bg-white border-b hover:bg-gray-100 even:bg-gray-50">
                    <td class="px-6 py-4 w-max">
                      #{{$customer->id}}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap truncate ... w-max">
                      {{$customer->social_name}}
                    </th>
                     <td class="px-6 py-4 ">
                        @if(in_array("viewContracts", $permissions))                     
                          <a href="{{route('contracts.index.customer', [$customer->id, $companyId])}}" type='button' 
                             class='font-medium bg-gray-300 text-white rounded-md px-2 hover:bg-gray-500 px-2 py-1 w-max'>
                            {{$customer->contracts->count()}} {{ __('Contracts') }}
                          </a>
                        @endif  
                    </td>
                                        
                    @if($active == true)
                      <td class="px-6 py-4 text-right w-120">
                        <div class="flex justify-end">
                        @if(in_array("customer.show", $permissions))
                            <a href="#" 
                            class="font-medium bg-indigo-300 text-white rounded-md px-2 hover:bg-indigo-500 px-2 py-1 mx-1" 
                            wire:click="showCustomer({{$customer->id}})" wire:loading.attr="disabled">{{__('Detail')}}</a>
                        @endif
                        @if(in_array("customer.edit", $permissions))
                            <a  href="#" 
                            class="font-medium bg-blue-300 text-white rounded-md px-2 hover:bg-blue-500 px-2 py-1 mx-1" 
                            wire:click="editCustomer({{$customer->id}})" wire:loading.attr="disabled">{{__('Edit')}}</a>
                        @endif
                        @if(in_array("customer.delete", $permissions))
                            <a  href="#" 
                            class="font-medium bg-red-300 text-white rounded-md px-2 hover:bg-red-500 px-2 py-1 mx-1"
                            wire:click="confirmCustomerDeletion({{$customer->id}})" wire:loading.attr="disabled">{{__('Delete')}}</a>
                        @endif  
                        </div>  
                      </td>
                    @else
                      <td class="px-6 py-4 text-right w-120">
                        <div class="flex justify-end">
                        @if(in_array("customer.restore", $permissions))                    
                            <a  href="#" 
                            class="font-medium bg-blue-300 text-white rounded-md px-2 hover:bg-blue-500 px-2 py-1 mx-1" 
                            wire:click="confirmRestoreCustomer({{$customer->id}})" wire:loading.attr="disabled">{{__('Restore')}}
                            </a>
                        @endif
                        @if(in_array("customer.forceDelete", $permissions))
                            <a  href="#" 
                            class="font-medium bg-red-300 text-white rounded-md px-2 hover:bg-red-500 px-2 py-1 mx-1"
                            wire:click="confirmForceCustomerDeletion({{$customer->id}})" wire:loading.attr="disabled">{{__('Force Delete')}}
                            </a>
                        @endif
                        </div>
                      </td>
                    @endif
                  </tr>
                  @empty
                    {{-- empty expr --}}
                  @endforelse
                  
                </tbody>
              </table>
              </div>
            </div>
            {{--Pagination--}}
            <div class="p-4">
            {{$customers->links()}}
            </div>
          </div>

          {{--End Component Table--}}
        
      </div>
    </div>
  </div>


 

<!-- Delete customer Modal -->
<x-jet-dialog-modal wire:model="deleteCustomer">
    <x-slot name="title">
        {{ __('Delete Customer') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to delete this customer? Once your customer account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete this customer account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-customer.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="passwordUser"
                        wire:keydown.enter="deleteCustomer" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('deleteCustomer')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="deleteCustomer" wire:loading.attr="disabled">
            {{ __('Delete Customer Account') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>

<!-- Force Delete Customer Modal -->
<x-jet-dialog-modal wire:model="forceDeleteCustomer">
    <x-slot name="title">
        {{ __('Force Delete Customer') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to force delete this customer? Once your customer account is force deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently customer your customer account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-customer.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="password"
                        wire:keydown.enter="forceDeleteCustomer" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('forceDeleteCustomer')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="forceDeleteCustomer" wire:loading.attr="disabled">
            {{ __('Delete Customer') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>

<!-- restore Customer Modal -->
<x-jet-dialog-modal wire:model="restoreCustomer">
    <x-slot name="title">
        {{ __('Restore Customer') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to restore this customer? Once your customer account is restore, all of its resources and data will be permanently restore. Please enter your password to confirm you would like to permanently restore your customer account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-customer.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="password"
                        wire:keydown.enter="restoreCustomer" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('restoreCustomer')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="restoreCustomer" wire:loading.attr="disabled">
            {{ __('Restore Customer Account') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>


<!-- Create New Customer Modal -->
  <x-jet-dialog-modal wire:model="createNewCustomer"> 
      <x-slot name="title">
          {{ __('Create New Customer') }}
      </x-slot>

      <x-slot name="content">        


        <div class="col-span-6 sm:col-span-4 py-2">
            <x-jet-label for="name" value="{{ __('Social name') }}" />
            <x-jet-input  class="block mt-1 w-full" type="text"  required autofocus wire:model="social_name"/>
            <x-jet-input-error for="social_name" class="mt-2" />
        </div>   
        <div class="col-span-6 sm:col-span-4 py-2">
            <x-jet-label for="name" value="{{ __('Fantasy name') }}" />
            <x-jet-input  class="block mt-1 w-full" type="text"  autofocus wire:model="fantasy_name"/>
            <x-jet-input-error for="fantasy_name" class="mt-2" />
        </div>   
        
        
        <div class="col-span-6 sm:col-span-4 py-2">
            <x-jet-label for="name" value="{{ __('Dni') }}" />
            <x-jet-input  class="block mt-1 w-full" type="text"  autofocus wire:model="dni"/>
            <x-jet-input-error for="dni" class="mt-2" />
        </div> 
        <div class="col-span-6 sm:col-span-4 py-2">
            @if ($logo_photo_path)
                {{ __('Photo Preview:') }}
                <img width="100px" src="{{ $logo_photo_path->temporaryUrl() }}">
            @endif
            <x-jet-label for="name" value="{{ __('Logo') }}" />
            <x-jet-input  class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" type="file"  autofocus wire:model="logo_photo_path"/>
            <x-jet-input-error for="logo" class="mt-2" />
        </div>  
       
        <div class="col-span-6 sm:col-span-4 py-2">
            <x-jet-label for="detail" value="{{ __('Detail') }}" />
            
            <textarea class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" type="text" rows="4" autofocus wire:model="detail"></textarea>
            <x-jet-input-error for="detail" class="mt-2" />
        </div>        
            
         
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="$toggle('createNewCustomer')" wire:loading.attr="disabled">
              {{ __('Cancel') }}
          </x-jet-secondary-button>

          <x-jet-danger-button class="ml-3" wire:click="saveCustomer" wire:loading.attr="disabled">
              {{ __('Create Customer Account') }}
          </x-jet-danger-button>
      </x-slot>
  </x-jet-dialog-modal>

  


  <!-- Edit Customer Modal -->
  <x-jet-dialog-modal wire:model="editCustomer"> 
      <x-slot name="title">
          {{ __('Update Customer Account Data') }}
      </x-slot>

      <x-slot name="content">
          
            <div class="col-span-6 sm:col-span-4 py-2">
            <x-jet-label for="name" value="{{ __('Social name') }}" />
            <x-jet-input  class="block mt-1 w-full" type="text"  required autofocus wire:model="social_name"/>
            <x-jet-input-error for="social_name" class="mt-2" />
        </div>   
        <div class="col-span-6 sm:col-span-4 py-2">
            <x-jet-label for="name" value="{{ __('Fantasy name') }}" />
            <x-jet-input  class="block mt-1 w-full" type="text"  autofocus wire:model="fantasy_name"/>
            <x-jet-input-error for="fantasy_name" class="mt-2" />
        </div>   
        
        <div class="col-span-6 sm:col-span-4 py-2">
            <x-jet-label for="name" value="{{ __('Dni') }}" />
            <x-jet-input  class="block mt-1 w-full" type="text"  autofocus wire:model="dni"/>
            <x-jet-input-error for="dni" class="mt-2" />
        </div> 
        <div class="col-span-6 sm:col-span-4 py-2">
            
            @if($logo_saved != null)
              Imagen Guardada:
              <img class="rounded-xl" src="{{ url('storage/companies/'.$logo_saved) }}" alt="" width="200px">

            @endif

            @if ($logo_photo_path)
                {{ __('Photo Preview:') }}
                <img width="100px" src="{{ $logo_photo_path->temporaryUrl() }}">
            @endif
            <x-jet-label for="name" value="{{ __('Logo') }}" />
            <x-jet-input  class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" type="file"  autofocus wire:model="logo_photo_path"/>
            <x-jet-input-error for="logo_photo_path" class="mt-2" />
        </div>  

        <div class="col-span-6 sm:col-span-4 py-2">
            <x-jet-label for="detail" value="{{ __('Detail') }}" />
            
            <textarea class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" type="text" rows="4" autofocus wire:model="detail"></textarea>
            <x-jet-input-error for="detail" class="mt-2" />
        </div>

                                 
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="$toggle('editCustomer')" wire:loading.attr="disabled">
              {{ __('Cancel') }}
          </x-jet-secondary-button>
          @if(in_array("customer.edit", $permissions))
          <x-jet-danger-button class="ml-3" wire:click="updateCustomer" wire:loading.attr="disabled">
              {{ __('Update Customer Data') }}
          </x-jet-danger-button>
          @endif
      </x-slot>
  </x-jet-dialog-modal>


  <!-- Show Customer Modal -->
  <x-jet-dialog-modal wire:model="showCustomer"> 
      <x-slot name="title">
          {{ __('Show Customer Data') }}
      </x-slot>

      <x-slot name="content">
          
        <div class="col-span-6 sm:col-span-4">
            @if($customerShow)
              <!-- Start: Invoice -->
                <div class="w-full">  
                  <div class="flex justify-between">
                    <div class="text-xs text-gray-400">{{__('Register')}} #{{$customerShow->id}}</div>
                    <div class="text-xs text-gray-400">{{__('Created at')}}: {{$customerShow->created_at}}</div>

                  </div>            
                  
                  <hr>
                  <div class="w-full flex justify-between mt-10">                   
                    <div class="text-sm text-gray-400">{{__('Name')}}:</div>                          
                    <div class="text-sm text-gray-600 uppercase">{{$customerShow->social_name}}</div>                            
                  </div> 
                  
                                    
                </div>              
              <!-- END: Invoice -->
            @endif

        </div>        
         
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="closeShowCustomer()" wire:loading.attr="disabled">
              {{ __('Return') }}
          </x-jet-secondary-button>      
      </x-slot>
  </x-jet-dialog-modal>

</div>
