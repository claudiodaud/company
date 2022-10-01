<?php

namespace App\Models;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
     use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'contract_id',
        'customer_id',
    ];

    /**
     * Quote belongs to Product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Product::class);
    }

    /**
     * Quote belongs to Service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function services()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Service::class);
    }


    /**
     * Quote belongs to Contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contract()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->belongsTo(Contract::class);
    }

    /**
     * Quote belongs to Customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->belongsTo(Customer::class);
    }

    
}
