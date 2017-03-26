<?php

namespace Core\Series\Episode;

use Illuminate\Database\Eloquent\Model;
use Railken\Laravel\Manager\ModelContract;

class Episode extends Model implements ModelContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'episode';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}