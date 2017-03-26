<?php

namespace Core\Series\Series;

use Railken\Laravel\Manager\ModelContract;
use Core\Resource\Resource;

class Series extends Resource implements ModelContract
{   

    protected $morphClass = 'series';
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'series';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'overview', 'status'];
}