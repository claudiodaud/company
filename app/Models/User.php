<?php

namespace App\Models;

use App\Models\Box;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
       
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function companies()
    {
        // belongsTo(RelatedModel, foreignKey = service_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Company::class);
    }

    /**
     * User belongs to Contracts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contracts()
    {
        // belongsTo(RelatedModel, foreignKey = contracts_id, keyOnRelatedModel = id)
        return $this->belongsToMany(Contract::class);
    }

    
    /**
     * User morphs to many (many-to-many) .
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles()
    {
        // morphToMany(RelatedModel, morphName, pivotTable = ables, thisKeyOnPivot = able_id, otherKeyOnPivot = _id)
        return $this->morphToMany(Role::class, 'model','model_has_roles','model_id','role_id');
    }

    /**
     * User morphs to many (many-to-many) .
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function permissions()
    {
        // morphToMany(RelatedModel, morphName, pivotTable = ables, thisKeyOnPivot = able_id, otherKeyOnPivot = _id)
        return $this->morphToMany(Permission::class, 'model','model_has_permissions','model_id','permission_id');
    }

   
}
