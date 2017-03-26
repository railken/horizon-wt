<?php

namespace Core\Series\Season;

use Illuminate\Database\Eloquent\Model;
use Railken\Laravel\Manager\ModelContract;

class Season extends Model implements ModelContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'season';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}