<div>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      <span class="text-gray-400 uppercase">{{ __(App\Models\Company::find($companyId)->name.' /') }}</span> 
      <span class="text-gray-700">{{__(' Products Index')}}</span>
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
                  @if(in_array("product.filter", $permissions))
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                  @endif   
                  
                  @if(in_array("product.filter", $permissions))
                      <input type="text" id="table-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block pl-10 py-3  sm:mx-0 sm:mr-2  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 w-full sm:w-60" placeholder="Search for items" wire:model="search">
                  @endif
                </div>
                    
                @if(in_array("product.deleted", $permissions))    
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
                  @if(in_array("product.create", $permissions))
                    <a wire:click="$toggle('createNewProduct')" type='button' class='inline-flex items-center bg-black px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:text-gray-200 hover:bg-gray-700 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-40 py-3 sm:py-0 mt-2 sm:mt-0 sm:mx-2 mr-1'>
                        {{ __('Create New') }}
                    </a>
                  @endif
                  @if(in_array("product.download", $permissions))
                    <a wire:click="downloadProducts" type='button' class='inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 hover:bg-gray-200 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition w-full sm:w-40 py-3 sm:py-0 mt-2 sm:mt-0 sm:mx-2 ml-1 mr-1'>
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
                  <div class="text-sm font-base px-4 text-red-800 ">{{ __('Product register successfull deleted') }}</div>  
                </div>        
              </x-jet-action-message>  

              <x-jet-action-message class="" on="forceDeleted">
                <div class="text-xl font-normal  max-w-full flex-initial bg-fuchsia-100 p-4 my-4 rounded-lg border border-fuchsia-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-fuchsia-900 ">{{ __('Product register successfull force deleted') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="restore">
                <div class="text-xl font-normal  max-w-full flex-initial bg-blue-100 p-4 my-4 rounded-lg border border-blue-800 flex justify-start">
                  <div class="text-sm font-base px-4 text-blue-900 ">{{ __('Product register successfull restored') }}</div>  
                </div>        
              </x-jet-action-message> 

              <x-jet-action-message class="" on="created">
                <div class="text-xl font-normal  max-w-full flex-initial bg-green-100 p-4 my-4 rounded-lg border border-green-800 ">
                  <div class="text-sm font-base px-4 text-green-800 ">{{ __('Product register successfull created') }}</div>  
                </div>        
              </x-jet-action-message>  

              <x-jet-action-message class="" on="updated">
                <div class="text-xl font-normal  max-w-full flex-initial bg-indigo-100 p-4 my-4 rounded-lg border border-indigo-800 ">
                  <div class="text-sm font-base px-4 text-indigo-800 ">{{ __('Product register successfull update') }}</div>  
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
                    <th scope="col" class="px-6 py-3 w-max rounded-tr-lg rounded-br-lg text-right">
                      {{__('Actions')}}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($products as $product)
                    <tr class="bg-white border-b hover:bg-gray-100 even:bg-gray-50">
                    <td class="px-6 py-4 w-max">
                      #{{$product->id}}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap truncate ... w-max ">
                      {{$product->name}}
                    </th>
                                        
                    @if($active == true)
                      <td class="px-6 py-4 text-right w-120">
                        <div class="flex justify-end">
                        @if(in_array("product.show", $permissions))
                        <a href="#" class="font-medium bg-indigo-300 text-white rounded-md px-2 hover:bg-indigo-500 px-2 py-1 mx-1" 
                            wire:click="showProduct({{$product->id}})" wire:loading.attr="disabled">{{__('Detail')}}</a>
                        @endif
                        @if(in_array("product.edit", $permissions))
                        <a  href="#" 
                            class="font-medium bg-blue-300 text-white rounded-md px-2 hover:bg-blue-500 px-2 py-1 mx-1" 
                            wire:click="editProduct({{$product->id}})" wire:loading.attr="disabled">{{__('Edit')}}</a>
                        @endif    
                        @if(in_array("product.delete", $permissions))
                        <a  href="#" 
                            class="font-medium bg-red-300 text-white rounded-md px-2 hover:bg-red-500 px-2 py-1 mx-1"
                            wire:click="confirmProductDeletion({{$product->id}})" wire:loading.attr="disabled">{{__('Delete')}}</a>
                        @endif
                        </div>
                      </td>
                    @else
                      <td class="px-6 py-4 text-right w-120">
                        <div class="flex justify-end">
                        @if(in_array("product.restore", $permissions))                    
                        <a  href="#" 
                            class="font-medium bg-blue-300 text-white rounded-md px-2 hover:bg-blue-500 px-2 py-1 mx-1" 
                            wire:click="confirmRestoreProduct({{$product->id}})" wire:loading.attr="disabled">{{__('Restore')}}</a>
                        @endif
                        @if(in_array("product.forceDelete", $permissions))
                        <a  href="#" 
                            class="font-medium bg-red-300 text-white rounded-md px-2 hover:bg-red-500 px-2 py-1 mx-1"
                            wire:click="confirmForceProductDeletion({{$product->id}})" wire:loading.attr="disabled">{{__('Force Delete')}}
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
            {{$products->links()}}
            </div>
          </div>

          {{--End Component Table--}}
        
      </div>
    </div>
  </div>


 

<!-- Delete product Modal -->
<x-jet-dialog-modal wire:model="deleteProduct">
    <x-slot name="title">
        {{ __('Delete Product') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to delete this product? Once your product account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete this product account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-product.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="passwordProduct"
                        wire:keydown.enter="deleteProduct" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('deleteProduct')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="deleteProduct" wire:loading.attr="disabled">
            {{ __('Delete Product Account') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>

<!-- Force Delete Product Modal -->
<x-jet-dialog-modal wire:model="forceDeleteProduct">
    <x-slot name="title">
        {{ __('Force Delete Product') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to force delete this product? Once your product account is force deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your company account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-product.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="passwordProduct"
                        wire:keydown.enter="forceDeleteProduct" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('forceDeleteProduct')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="forceDeleteProduct" wire:loading.attr="disabled">
            {{ __('Delete Product Account') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>

<!-- restore Product Modal -->
<x-jet-dialog-modal wire:model="restoreProduct">
    <x-slot name="title">
        {{ __('Restore Product') }}
    </x-slot>

    <x-slot name="content">
        {{ __('Are you sure you want to restore this product? Once your product account is restore, all of its resources and data will be permanently restore. Please enter your password to confirm you would like to permanently restore your product account.') }}

        <div class="mt-4" x-data="{}" x-on:confirming-delete-product.window="setTimeout(() => $refs.password.focus(), 250)">
            <x-jet-input type="password" class="mt-1 block w-3/4"
                        placeholder="{{ __('Write your password here') }}"
                        x-ref="password"
                        wire:model.defer="passwordProduct"
                        wire:keydown.enter="restoreProduct" />

            <x-jet-input-error for="password" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('restoreProduct')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-3" wire:click="restoreProduct" wire:loading.attr="disabled">
            {{ __('Restore Product Account') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>


<!-- Create New product Modal -->
  <x-jet-dialog-modal wire:model="createNewProduct"> 
      <x-slot name="title">
          {{ __('Create New Product') }}
      </x-slot>

      <x-slot name="content">        


            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" wire:model="name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>
         
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="$toggle('createNewProduct')" wire:loading.attr="disabled">
              {{ __('Cancel') }}
          </x-jet-secondary-button>

          <x-jet-danger-button class="ml-3" wire:click="saveProduct" wire:loading.attr="disabled">
              {{ __('Create Product Account') }}
          </x-jet-danger-button>
      </x-slot>
  </x-jet-dialog-modal>


  <!-- Edit product Modal -->
  <x-jet-dialog-modal wire:model="editProduct"> 
      <x-slot name="title">
          {{ __('Update Product Account Data') }}
      </x-slot>

      <x-slot name="content">
          
            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" wire:model="name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>
                               
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="$toggle('editProduct')" wire:loading.attr="disabled">
              {{ __('Cancel') }}
          </x-jet-secondary-button>
          @if(in_array("product.edit", $permissions))
          <x-jet-danger-button class="ml-3" wire:click="updateProduct" wire:loading.attr="disabled">
              {{ __('Update Product Account Data') }}
          </x-jet-danger-button>
          @endif
      </x-slot>
  </x-jet-dialog-modal>


  <!-- Show Product Modal -->
  <x-jet-dialog-modal wire:model="showProduct"> 
      <x-slot name="title">
          {{ __('Show Product Account Data') }}
      </x-slot>

      <x-slot name="content">
          
        <div class="col-span-6 sm:col-span-4">
            @if($productShow)
              <!-- Start: Invoice -->
                <div class="w-full">  
                  <div class="flex justify-between">
                    <div class="text-xs text-gray-400">{{__('Register')}} #{{$productShow->id}}</div>
                    <div class="text-xs text-gray-400">{{__('Created at')}}: {{$productShow->created_at}}</div>

                  </div>            
                  
                  <hr>
                  <div class="w-full flex justify-between mt-10">                   
                    <div class="text-sm text-gray-400">{{__('Name')}}:</div>                          
                    <div class="text-sm text-gray-600 uppercase">{{$productShow->name}}</div>                            
                  </div> 
                                                     
                </div>              
              <!-- END: Invoice -->
            @endif
        </div>        
         
      </x-slot>

      <x-slot name="footer">
          <x-jet-secondary-button wire:click="closeShowProduct()" wire:loading.attr="disabled">
              {{ __('Return') }}
          </x-jet-secondary-button>      
      </x-slot>
  </x-jet-dialog-modal>   

     

</div>