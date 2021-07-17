<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $guarded = ['id', 'created_at'];

    public function getExifAttribute($value)
    {
        return json_decode($value);
    }
}
