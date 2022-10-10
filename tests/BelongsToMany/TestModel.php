<?php

namespace Spatie\JsonApiPaginate\Test\BelongsToMany;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $guarded = [];

    public function results()
    {
        return $this->belongsToMany(ResultModel::class);
    }
}
