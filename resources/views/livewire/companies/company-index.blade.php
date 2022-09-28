<div>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Companies Index') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">                
        


          {{--Component Table--}}

          <div class=" shadow-md sm:rounded-lg">
            <div class="p-4 ">
              <div class="block sm:flex sm:justify-between">
                <div class="block sm:flex justify-start ">
                  <div class="relative">
                  @if(in_array("company.filter", $permissions))
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                  @endif   
                  
                  @if(in_array("company.filter", $permissions))
                      <input type="text" id="table-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block pl-10 py-3  sm:mx-0 sm:mr-2  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 w-full sm:w-60" placeholder="Search for items" wire:model="search">
                  @endif
                </div>
                    
                @if(in_array("company.deleted", $permissions))    
                  @if($active == true)
                    <a wire:click.prevent="active(false)" type='button' class='inline-flex items-center  px-2 sm:px-2 py-3 sm:mx-2 sm:py-0 mt-2 sm:mt-0 bg-white border border-gray-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest shadow-sm hover:text-red-500 hover:bg-red-50 focus:outline-none focus:border-gary-300 focus:ring focus:ring-blue-200 active:text-red-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-40'>
                        {{ __('Deleted Registers') }}
                    </a>
                  @elseif($active == false)
                    <a wire:click.prevent="active(true)" type='button' class='inline-flex items-center px-2 sm:px-2 py-3 sm:mx-2 sm:py-0 mt-2 sm:mt-0 bg-white border border-gray-300 rounded-md font-semibold text-xs text-green-700 uppercase tracking-widest shadow-sm hover:text-green-500 hover:bg-green-50 focus:outline-none focus:border-gray-300 focus:ring focus:ring-blue-200 active:text-green-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-40'>
                        {{ __('Actives Registers') }}
                    </a>
                  @endif
                @endif 
                </div> 
              
              
                <div class="flex sm:justify-end">
                  @if(in_array("company.create", $permissions))
                    <a wire:click="$toggle('createNewCompany')" type='button' class='inline-flex items-center bg-black px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:text-gray-200 hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-60 py-3 sm:py-0 mt-2 sm:mt-0 sm:mx-2 mr-1'>
                        {{ __('Create New') }}
                    </a>
                  @endif
                  @if(in_array("company.download", $permissions))
                    <a wire:click="downloadCompanies" type='button' class='inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 hover:bg-gray-200 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-60 py-3 sm:py-0 mt-2 sm:mt-0 sm:ml-2 ml-1'>
                        {{ __('Download') }}
                    </a>
                  @endif
                </div>
              </div>
            </div>
            <div class="mx-4">

              {{--Flash Messages--}}
              <x-jet-action-message class="" on="deleted">
                <div class="text-xl font-normal  max-w-full flex-initial bg-red-100 p-4 my-4 rounded-lg border border-red-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-red-800 ">{{ __('Company register successfull deleted') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="forceDeleted">
                <div class="text-xl font-normal  max-w-full flex-initial bg-fuchsia-100 p-4 my-4 rounded-lg border border-fuchsia-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-fuchsia-900 ">{{ __('Company register successfull force deleted') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="restore">
                <div class="text-xl font-normal  max-w-full flex-initial bg-blue-100 p-4 my-4 rounded-lg border border-blue-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-blue-900 ">{{ __('Company register successfull restored') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="created">
                <div class="text-xl font-normal  max-w-full flex-initial bg-green-100 p-4 my-4 rounded-lg border border-green-800 ">
                  <div class="text-sm font-base px-4 text-green-800 ">{{ __('Company register successfull created') }}</div>  
                </div>        
              </x-jet-action-message>  

              <x-jet-action-message class="" on="updated">
                <div class="text-xl font-normal  max-w-full flex-initial bg-indigo-100 p-4 my-4 rounded-lg border border-indigo-800 ">
                  <div class="text-sm font-base px-4 text-indigo-800 ">{{ __('Company register successfull update') }}</div>  
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
                    @if(in_array("viewUsers", $permissions))
                      <th scope="col" class="px-6 py-3 w-max">
                        {{ __('Users')}}
                      </th>
                    @endif
                    @if(in_array("viewContracts", $permissions))
                      <th scope="col" class="px-6 py-3 w-max">
                        {{ __('Contracts')}}
                      </th>
                    @endif
                    @if(in_array("viewRoles", $permissions))
                      <th scope="col" class="px-6 py-3 w-max">
                        {{ __('Roles')}}
                      </th>
                    @endif
                    @if(in_array("viewCustomers", $permissions))
                      <th scope="col" class="px-6 py-3 w-max">
                        {{ __('Customers')}}
                      </th>
                    @endif
                    @if(in_array("viewServices", $permissions))
                      <th scope="col" class="px-6 py-3 w-max">
                        {{ __('Services')}}
                      </th>
                    @endif
                    <th scope="col" class="px-6 py-3 w-max rounded-tr-lg rounded-br-lg text-right">
                      {{__('Actions')}}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($companies as $company)
                    <tr class="bg-white border-b hover:bg-gray-100 even:bg-gray-50">
                    <td class="px-6 py-4 w-max">
                      #{{$company->id}}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap truncate ... w-max">
                      {{$company->name}}
                    </th>
                    <td class="px-6 py-4 ">
                        @if(in_array("viewUsers", $permissions))                    
                          <a href="{{route('users.index.company', $company->id)}}" type='button' 
                             class='font-medium bg-gray-300 text-white rounded-md px-2 hover:bg-gray-500 px-2 py-1 w-max'>
                            {{$company->users->count()}} {{ __('Users') }}
                          </a>
                        @endif  
                    </td>
                    <td class="px-6 py-4 ">
                        @if(in_array("viewContracts", $permissions))                     
                          <a href="{{route('contracts.index.company', $company->id)}}" type='button' 
                             class='font-medium bg-gray-300 text-white rounded-md px-2 hover:bg-gray-500 px-2 py-1 w-max'>
                            {{$company->contracts->count()}} {{ __('Contracts') }}
                          </a>
                        @endif  
                    </td>
                    <td class="px-6 py-4 ">
                        @if(in_array("viewRoles", $permissions))                     
                          <a href="{{route('roles.index.company', $company->id)}}" type='button' 
                             class='font-medium bg-gray-300 text-white rounded-md px-2 hover:bg-gray-500 px-2 py-1 w-max'>
                            {{$company->roles->count()}} {{ __('Roles') }}
                          </a>
                        @endif  
                    </td>
                    <td class="px-6 py-4 ">
                        @if(in_array("viewCustomers", $permissions))                     
                          <a href="{{route('customers.index.company', $company->id)}}" type='button' 
                             class='font-medium bg-gray-300 text-white rounded-md px-2 hover:bg-gray-500 px-2 py-1 w-max'>
                            {{$company->customers->count()}} {{ __('Customers') }}
                          </a>
                        @endif  
                    </td>
                    <td class="px-6 py-4 ">
                        @if(in_array("viewServices", $permissions))                     
                          <a href="{{route('services.index.company', $company->id)}}" type='button' 
                             class='font-medium bg-gray-300 text-white rounded-md px-2 hover:bg-gray-500 px-2 py-1 w-max'>
                            {{$company->services->count()}} {{ __('Services') }}
                          </a>
                        @endif  
                    </td>
                    @if($active == true)
                      <td class="px-6 py-4 text-right w-120">
                        <div class="flex justify-end">
                        @if(in_array("company.show", $permissions))
                          <a href="#" class="font-medium bg-indigo-300 text-white rounded-md px-2 hover:bg-indigo-500 px-2 py-1 mx-1" 
                              wire:click="showCompany({{$company->id}})" wire:loading.attr="disabled">{{__('Detail')}}</a>
                        @endif
                        @if(in_array("company.edit", $permissions))
                          <a  href="#" 
                              class="font-medium bg-blue-300 text-white rounded-md px-2 hover:bg-blue-500 px-2 py-1 mx-1" 
                              wire:click="editCompany({{$company->id}})" wire:loading.attr="disabled">{{__('Edit')}}</a>
                        @endif
                        @if(in_array("company.delete", $permissions))
                        <a  href="#" 
                            class="font-medium bg-red-300 text-white rounded-md px-2 hover:bg-red-500 px-2 py-1 mx-1"
                            wire:click="confirmCompanyDeletion({{$company->id}})" wire:loading.attr="disabled">{{__('Delete')}}</a>
                        @endif  
                        </div>
                      </td>
                    @else
                      <td class="px-6 py-4 text-right w-120">
                        <div class="flex justify-end">
                        @if(in_array("company.restore", $permissions))                    
                          <a  href="#" 
                              class="font-medium bg-blue-300 text-white rounded-md px-2 hover:bg-blue-500 px-2 py-1 mx-1" 
                              wire:click="confirmRestoreCompany({{$company->id}})" wire:loading.attr="disabled">{{__('Restore')}}</a>
                        @endif
                        @if(in_array("company.forceDelete", $permissions))
                          <a  href="#" 
                              class="font-medium bg-red-300 text-white rounded-md px-2 hover:bg-red-500 px-2 py-1 mx-1"
                              wire:click="confirmForceCompanyDeletion({{$company->id}})" wire:loading.attr="disabled">{{__('Force Delete')}}
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
            {{$companies->links()}}
            </div>
          </div>

          {{--End Component Table--}}
        
      </div>
    </div>
  </div>


 

<!-- Delete Company Modal -->
<x-jet-dialog-modal wire:model="deleteCompany">
    <x-slot name="title">
        {{ __('Delete Company') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to delete this company? Once your company account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your company account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="password"
                        wire:keydown.enter="deleteCompany" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('deleteCompany')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="deleteCompany" wire:loading.attr="disabled">
            {{ __('Delete Company Account') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>

<!-- Force Delete Company Modal -->
<x-jet-dialog-modal wire:model="forceDeleteCompany">
    <x-slot name="title">
        {{ __('Force Delete Company') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to force delete this company? Once your company account is force deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your company account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="password"
                        wire:keydown.enter="forceDeleteCompany" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('forceDeleteCompany')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="forceDeleteCompany" wire:loading.attr="disabled">
            {{ __('Delete Company Account') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>

<!-- restore Company Modal -->
<x-jet-dialog-modal wire:model="restoreCompany">
    <x-slot name="title">
        {{ __('Restore Company') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to restore this company? Once your company account is restore, all of its resources and data will be permanently restore. Please enter your password to confirm you would like to permanently restore your company account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="password"
                        wire:keydown.enter="restoreCompany" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('restoreCompany')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="restoreCompany" wire:loading.attr="disabled">
            {{ __('Restore Company Account') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>


<!-- Create New Company Modal -->
  <x-jet-dialog-modal wire:model="createNewCompany"> 
      <x-slot name="title">
          {{ __('Create New Company') }}
      </x-slot>

      <x-slot name="content">
          
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input  class="block mt-1 w-full" type="text"  required autofocus wire:model="name"/>
            <x-jet-input-error for="name" class="mt-2" />
        </div>        
         
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="$toggle('createNewCompany')" wire:loading.attr="disabled">
              {{ __('Cancel') }}
          </x-jet-secondary-button>

          <x-jet-danger-button class="ml-3" wire:click="saveCompany" wire:loading.attr="disabled">
              {{ __('Create Company Account') }}
          </x-jet-danger-button>
      </x-slot>
  </x-jet-dialog-modal>


  <!-- Edit Company Modal -->
  <x-jet-dialog-modal wire:model="editCompany"> 
      <x-slot name="title">
          {{ __('Update Company Account Data') }}
      </x-slot>

      <x-slot name="content">
          
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input  class="block mt-1 w-full" type="text"  value="" required autofocus wire:model="name"/>
            <x-jet-input-error for="name" class="mt-2" />
        </div>        
         
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="$toggle('editCompany')" wire:loading.attr="disabled">
              {{ __('Cancel') }}
          </x-jet-secondary-button>

          <x-jet-danger-button class="ml-3" wire:click="updateCompany" wire:loading.attr="disabled">
              {{ __('Update Company Account Data') }}
          </x-jet-danger-button>
      </x-slot>
  </x-jet-dialog-modal>


  <!-- Show Company Modal -->
  @if($companyShow)
  <x-jet-dialog-modal wire:model="showCompany"> 
      <x-slot name="title">
          {{ __('Show Company Account Data') }}
      </x-slot>

      <x-slot name="content">
          
        <div class="col-span-6 sm:col-span-4">
            
              <!-- Start: Invoice -->
                <div class="w-full">  
                  <div class="flex justify-between">
                    <div class="text-xs text-gray-400">{{__('Register')}} #{{$companyShow->id}}</div>
                    <div class="text-xs text-gray-400">{{__('Created at')}}: {{$companyShow->created_at}}</div>

                  </div>            
                  
                  <hr>
                  <div class="w-full flex justify-between mt-10">                   
                    <div class="text-sm text-gray-400">{{__('Name')}}:</div>                          
                    <div class="text-sm text-gray-600">{{$companyShow->name}}</div>                            
                  </div> 
                  @if(count($companyShow->users) > 0 )
                  <hr>                    
                  <div>
                    <div class="mt-6">{{__('Associated users')}}</div>
                    <div>
                      @foreach ($companyShow->users as $user)
                        <div class="w-full flex justify-between mt-4">                   
                          <div class="text-xs text-gray-600">{{__('Name')}}: {{$user->name}}<br>
                            <div class="text-xs text-gray-400">{{__('Email')}}: {{$user->email}}</div>  
                          </div>                          
                                                    
                        </div>   
                      @endforeach
                    </div>
                  </div>
                  @endif
                </div>              
              <!-- END: Invoice -->
            
        </div>        
         
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="closeShowCompany()" wire:loading.attr="disabled">
              {{ __('Return') }}
          </x-jet-secondary-button>
          
          
               
      </x-slot>
  </x-jet-dialog-modal>   
  @endif

</div>

