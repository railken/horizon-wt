<?php

namespace Core\ResourceContainer;

use Illuminate\Database\Eloquent\Model;
use Railken\Laravel\Manager\ModelContract;

use Core\User\User;
use Core\ResourceContainer\ResourceContainer;

class ResourceContainerUser extends Model implements ModelContract
{

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'resource_containers_users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'resource_container_id', 'user_id', 'status', 'note', 'review', 'rating',
    ];

    /**
     * User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Resource container
     */
    public function resource_container()
    {
        return $this->belongsTo(ResourceContainer::class);
    }
}
