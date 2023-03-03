<?php

namespace App\Models;

use App\Models\Box;
use App\Models\Condition;
use App\Models\Product;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model 
{
    use HasFactory, SoftDeletes;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'fantasy_name',
        'social_name',                
        'dni',
        'logo_photo_path',        
        'detail',
    ];

    
    
    

    /**
     * Company belongs to Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->belongsToMany(User::class);
    }

    /**
     * Company belongs to Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roles()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Role::class);
    }

   
    /**
     * Company belongs to Services.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function services()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->hasMany(Service::class);
    }

    /**
     * Company belongs to Products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->hasMany(Product::class);
    }

    /**
     * Company belongs to Products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conditions()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->hasMany(Condition::class);
    }


    /**
     * Company has many Contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = company_id, localKey = id)
        return $this->hasMany(Contract::class);
    }

    
    /**
     * Company belongs to Customers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customers()
    {
        // belongsTo(RelatedModel, foreignKey = customers_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Company::class,'supplier_customer','supplier_id','customer_id');
    }


    /**
     * Company belongs to Customers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        // belongsTo(RelatedModel, foreignKey = customers_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Company::class,'supplier_customer','customer_id','supplier_id');
    }



    
}
