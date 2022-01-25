<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = ['name'];


    // Relationships
    public function parts()
    {
        return $this->hasMany(Part::class);
    }
}