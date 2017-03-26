<?php

namespace Core\ResourceContainer;

use Illuminate\Database\Eloquent\Model;
use Railken\Laravel\Manager\ModelContract;

use Core\Series\Series\Series;
use Core\Manga\Manga\Manga;

class ResourceContainer extends Model implements ModelContract
{

	protected $types = [
        'series' => Series::class,
        'manga' => Manga::class
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'resource_type', 'resource_id', 'database_name', 'database_id'
    ];


    /**
     * Get the resource
     */
    public function resource()
    {
    	return $this->morphTo();
    }

    public function getResourceTypeAttribute($type) {
        // transform to lower case
        $type = strtolower($type);

        // to make sure this returns value from the array
        return array_get($this->types, $type, $type);

        // which is always safe, because new 'class'
        // will work just the same as new 'Class'
    }
}
