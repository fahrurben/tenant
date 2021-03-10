<?php
namespace Kyrosoft\Tenant\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tenant class
 * @property int $id
 * @property string $name
 * @property string $sub_domain
 * @property \DateTime $created_at
 * @property int $created_by
 * @property \DateTime $updated_at
 * @property int $updated_by
 * @property \DateTime $deleted_at
 *
 */
class Tenant extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tenant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sub_domain',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}