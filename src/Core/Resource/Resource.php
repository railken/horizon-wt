<?php

namespace Core\Resource;

use Illuminate\Database\Eloquent\Model;
use Railken\Laravel\Manager\ModelContract;

use Core\ResourceContainer\ResourceContainer;

abstract class Resource extends Model implements ModelContract
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'container_id', 
        'name',
        'overview',
        'status', // ongoing (including paused), canceled, ended
        'poster',
        'banner'
    ];

    /**
     * Get the container associated with the resource
     */
    public function container()
    {
        return $this->morphMany(ResourceContainer::class);
    }

    /**
     * Get type attribute
     *
     * @return string
     */
    public function getTypeAttribute($value = null)
    {
        return $this->morphClass;
    }
}