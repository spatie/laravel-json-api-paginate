<?php

namespace Spatie\JsonApiPaginate\Test\BelongsToMany;

use Illuminate\Database\Eloquent\Model;

class ResultModel extends Model
{
    protected $guarded = [];

    public function tests()
    {
        return $this->belongsToMany(TestModel::class);
    }
}
