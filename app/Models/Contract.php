<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Contract extends Model
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
     * Contract belongs to Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        // belongsTo(RelatedModel, foreignKey = users_id, keyOnRelatedModel = id)
        return $this->belongsToMany(User::class);
    }

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


}
