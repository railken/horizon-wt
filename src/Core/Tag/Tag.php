<?php

namespace Core\Tag;

use Illuminate\Database\Eloquent\Model;
use Railken\Laravel\Manager\ModelContract;

use Core\ResourceContainer\ResourceContainer;

class Tag extends Model implements ModelContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'aliases', 'description'];

    /**
     * Get resource containers
     *
     * @return Relation
     */
    public function resource_containers()
    {
        return $this->belongsToMany(ResourceContainer::class, 'resource_containers_tags', 'tag_id', 'resource_container_id');
    }

    public function setAliasesAttribute($value)
    {
        $this->attributes['aliases'] = json_encode($value);
    }

    public function getAliasesAttribute($value)
    {

        return json_decode($value, true);
    }
}