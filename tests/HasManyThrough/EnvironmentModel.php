<?php

namespace Spatie\JsonApiPaginate\Test\HasManyThrough;

use Illuminate\Database\Eloquent\Model;

class EnvironmentModel extends Model
{
    protected $guarded = [];

    public function test()
    {
        return $this->belongsTo(TestModel::class, 'test_model_id');
    }

    public function results()
    {
        return $this->hasMany(ResultModel::class);
    }
}
