<?php

namespace App\Http\Livewire\Products;



use App\Exports\CompanyProductsExport;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\withMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Spatie\Permission\Exceptions\UnauthorizedException;


class ProductIndexCompany extends Component
{
    use WithPagination;



    public $deleteProduct = false;
    public $forceDeleteProduct = false;
    public $restoreProduct = false;
    public $productId; 
    public $passwordProduct;
    public $companyId = null; 


    public $createNewProduct = false; 


    public $productEdit;
    public $productShow;

    
    public $editProduct = false;
    public $showProduct = false;

    public $search; 


    public $name; 
    
    public $active = true;

   
    public $permissions;
        

    public function mount($id)
    {
        $this->companyId = $id ; 

        $this->getPermissions();
    }

    public function render()
    {
        $usersByCompany = Company::find($this->companyId)->users()->where(function($query) {
            $query->where('users.id',auth()->user()->id);
        });

        if(count($usersByCompany->get()) > 0){

            $productsByCompany = Company::find($this->companyId)->products();
            
            
            if ($this->active == true) {

                $products = $productsByCompany->Where(function($query) {
                                 $query  ->orWhere('products.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('products.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('products.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('products.id', 'DESC')->paginate(10);
            }else{

                 $products = $productsByCompany->Where(function($query) {
                                 $query  ->orWhere('products.name', 'like', '%'.$this->search.'%')
                                         ->orWhere('products.created_at', 'like', '%'.$this->search.'%')
                                         ->orWhere('products.updated_at', 'like', '%'.$this->search.'%');                            
                                    })->orderBy('products.id', 'DESC')->onlyTrashed()->paginate(10);
                                       
            }
     
            if(in_array("viewProducts", $this->permissions)){
                
                return view('livewire.products.product-index-company', [

                    'products' => $products,

                ]);

            }else{

                throw UnauthorizedException::forPermissions($this->permissions);

            }
        }else{

             throw UnauthorizedException::forPermissions($this->permissions);

        }
    }

    public function getPermissions()
    {
        $productWithRolesAndPermissions = User::where('id',auth()->user()->id)->with('roles')->first();
        $productWithDirectsPermissions = User::where('id',auth()->user()->id)->with('permissions')->first();
        
        
        $permissions = [];

        //find permissions for roles
        foreach ($productWithRolesAndPermissions->roles as $key => $role) {
           
            $role = Role::where('id',$role->id)->with('permissions')->first();
                
                foreach ($role->permissions as $key => $permission) {
                    array_push($permissions,$permission->name);
                }                
        }

        //find directs permissions
        foreach ($productWithDirectsPermissions->permissions as $key => $permission) {
        
            array_push($permissions,$permission->name);
                         
        }

        $this->permissions = array_unique($permissions);

        //dd($this->permissions);
    }

    
    public function updatingSearch()
    {
        $this->resetPage();        
    }

    public function confirmProductDeletion($productId)
    {
        $this->productId = $productId; 
        $this->deleteProduct = true;
    }

    public function confirmForceProductDeletion($productId)
    {
        $this->productId = $productId; 
        $this->forceDeleteProduct = true;
    }

    public function confirmRestoreProduct($productId)
    {
        $this->productId = $productId; 
        $this->restoreProduct = true;
    }

    public function deleteProduct()
    {

        if (! Hash::check($this->passwordProduct, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            Product::destroy($this->productId);


            $this->deleteProduct = false;
            $this->passwordProduct = null;
            $this->productId = null;
            $this->emit("deleted");

        }       
    }

    public function forceDeleteProduct()
    {

        if (! Hash::check($this->passwordProduct, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $product = Product::withTrashed()->find($this->productId);
            $product->forceDelete();
            $this->forceDeleteProduct = false;
            $this->passwordProduct = null;
            $this->productId = null;
            $this->emit("forceDeleted");

        }       
    }

    public function restoreProduct()
    {

        if (! Hash::check($this->passwordProduct, Auth::user()->password)) {

            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);

        }else{    

            $product = Product::withTrashed()->find($this->productId);
            $product->restore();
            $this->restoreProduct = false;
            $this->passwordProduct = null;
            $this->productId = null;
            $this->emit("restore");

        }       
    }

 
    public function saveProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',             
        ]);
    

        $product = Product::create([
            'name' => $this->name,       
        ]);

        $product->companies()->sync($this->companyId);

        $this->name = "";
        
        $this->createNewProduct = false; 
        $this->resetPage();
        $this->emit('created');
    }

    public function updatedCreateNewProduct()
    {
        if ($this->createNewProduct == false) {
            $this->name = "";
        }
    }

    public function editProduct($id)
    {
        
        $product = Product::find($id);   
        
        $this->product = $product;
        $this->name = $product->name;        
        $this->editProduct = true; 
    }

    public function updateProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
                         
        ]);               

        $product = Product::find($this->product->id)->update([

            'name' => $this->name,
        ]);        

        $this->name = null;     
        $this->product = null;
        $this->editProduct = false; 
        $this->emit('updated');
    }



    public function downloadProducts()
    {
       
        return (new CompanyProductsExport(['search' => $this->search], ['companyId' => $this->companyId], ['active' => $this->active]))->download('products.xlsx'); 
       
    }

    public function showProduct($id)
    {
        $this->productShow = Product::where('id',$id)->first();
        

        $this->showProduct = true;
    }

    public function closeShowProduct()
    {
        $this->showProduct = false;

        $this->productShow = null;        
    }


    public function active($active)
    {
        
        $this->active = $active;
    }



}