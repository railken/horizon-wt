<?php

namespace Core\ResourceContainer;

use Illuminate\Database\Eloquent\Model;
use Railken\Laravel\Manager\ModelContract;

use Core\Series\Series\Series;
use Core\Manga\Manga\Manga;
use Core\Tag\Tag;
use Core\Media\Media;

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
     *
     * @return Relation
     */
    public function resource()
    {
    	return $this->morphTo();
    }   

    /**
     * Get tags
     *
     * @return Relation
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'resource_containers_tags', 'resource_container_id', 'tag_id');
    }
    
    /**
     * Get media
     *
     * @return Relation
     */
    public function media()
    {
        return $this->belongsToMany(Media::class, 'resource_containers_media', 'resource_container_id', 'media_id');
    }
 

    /**
     * Get type morph resource
     *
     * @return string
     */
    public function getResourceTypeAttribute($type) {
        // transform to lower case
        $type = strtolower($type);

        // to make sure this returns value from the array
        return array_get($this->types, $type, $type);

        // which is always safe, because new 'class'
        // will work just the same as new 'Class'
    }

    /**
     * set type morph resource
     *
     * @return string
     */
    public function setResourceTypeAttribute($type) {

        $this->attributes['resource_type'] = array_search($type, $this->types);
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getTypeAttribute() {
        return $this->getOriginal('resource_type');
    }
}
