<?php

namespace Spatie\JsonApiPaginate\Test\HasManyThrough;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $guarded = [];

    public function environments()
    {
        return $this->hasMany(EnvironmentModel::class);
    }

    public function results()
    {
        return $this->hasManyThrough(ResultModel::class, EnvironmentModel::class);
    }
}
