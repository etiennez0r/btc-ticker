<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    use HasFactory;

    var $fillable = [
        'symbol',
        'price',
        'time',
    ];

    public static function createOrUpdate($attr, $values)
    {
        $ticker = self::firstOrNew($attr);
        
        foreach ($values as $k => $value)
            $ticker->$k = $value;

        $ticker->save();
    }
}
