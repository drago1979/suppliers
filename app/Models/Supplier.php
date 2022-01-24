<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'priority'];


    // Relationships

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parts()
    {
        return $this->hasMany(Part::class);
    }
}
