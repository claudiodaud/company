<?php

namespace App\Models;

use App\Models\Box;
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
        'name','user_id',
    ];

    
    
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
     * Company has many Contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = company_id, localKey = id)
        return $this->hasMany(Customer::class);
    }

    /**
     * Company belongs to Services.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function services()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Service::class);
    }

    /**
     * Company belongs to Products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Product::class);
    }
}
