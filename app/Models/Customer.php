<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'company_id',
    ];


    /**
     * Contract belongs to Companies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        // belongsTo(RelatedModel, foreignKey = companies_id, keyOnRelatedModel = id)
        return $this->belongsTo(Company::class);
    }

    /**
     * User has many Quote.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotes()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = user_id, localKey = id)
        return $this->hasMany(Quote::class);
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
}
