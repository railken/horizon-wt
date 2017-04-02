<?php

namespace Core\Media;

use Illuminate\Database\Eloquent\Model;
use Railken\Laravel\Manager\ModelContract;

class Media extends Model implements ModelContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'label', 'path', 'source', 'length'];
}