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
    protected $table = 'episodes';

    /**
     * The attribute dates
     *
     * @var array
     */
    protected $dates = ['aired_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'overview', 'number', 'season_number', 'series_id', 'season_id', 'aired_at'];
}