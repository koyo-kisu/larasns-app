<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    // get...Attributeという形式になっているメソッドがアクセサ
    // アクセサを利用してタグ名の最初に#をつける
    public function getHashtagAttribute(): string
    {
        return '#' . $this->name;
    }
}
