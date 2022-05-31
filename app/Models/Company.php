<?php

namespace App\Models;

use App\Models\Box;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model 
{
    use HasFactory;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    public function users()
    {
        // belongsTo(RelatedModel, foreignKey = service_id, keyOnRelatedModel = id)
        return $this->HasMany(User::class);
    }

    
}
