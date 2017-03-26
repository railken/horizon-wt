<?php

namespace Core\ResourceContainer;

use Illuminate\Database\Eloquent\Model;
use Core\Manager\ManagerEntityContract;

class ResourceContainer extends Model implements ManagerEntityContract
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'resource_type', 'resource_id', 'database_name', 'database_id'
    ];
}
