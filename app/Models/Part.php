<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'condition_id', 'category_id',
        'days_valid', 'part_number', 'part_description',
        'quantity', 'price'
    ];


    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Filters
    public function scopeFilterSupplierId(Builder $query, $supplier_id)
    {
        if(!empty($supplier_id)) {
            $query->whereIn('parts.supplier_id', $supplier_id);
        }
    }
}
