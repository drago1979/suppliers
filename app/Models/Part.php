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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Filters

    /**
     * @param Builder $query
     * @param $supplier_id
     */
    public function scopeFilterSupplierId(Builder $query, $supplier_id)
    {
        if (!empty($supplier_id)) {
            $query->whereIn('parts.supplier_id', $supplier_id);
        }
    }
}
