<?php

namespace Core\ResourceContainer;

use Illuminate\Database\Eloquent\Model;
use Railken\Laravel\Manager\ModelContract;

class ResourceContainer extends Model implements ModelContract
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
