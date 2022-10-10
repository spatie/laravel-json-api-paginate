<?php

namespace Spatie\JsonApiPaginate\Test\HasManyThrough;

use Illuminate\Database\Eloquent\Model;

class ResultModel extends Model
{
    protected $guarded = [];

    public function environment()
    {
        return $this->belongsTo(EnvironmentModel::class, 'environment_model_id');
    }
}
