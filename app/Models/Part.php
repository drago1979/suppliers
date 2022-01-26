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

//    public function getPartsForCsvDownloadFile($supplierId)
//    {
//        // Get supplier parts (collection) from DB
//        return Part::where('supplier_id', $supplierId)->get();
//
//        // Define which attributes are to be: removed, renamed+values changed;
//        // Attributes to remove: Works if attributes base model has: "id" & "name" attributes
//        $attributesToRemove = ['id', 'supplier_id'];
//        $attributesToRenameAndChangeValues = [
//            'condition_id' => 'condition',
//            'category_id' => 'category'
//        ];
//
//        // Create usable list of attributes to be changed
//        $attributesToRenameList = [];
//        foreach ($attributesToRenameAndChangeValues as $attribute) {
//            $attributesToRenameList[$attribute] = $this->getList($attribute);
//        }
//
//        $suppliersParts->transform(function (Part $part, $key) use ($attributesToRemove, $attributesToRenameAndChangeValues, $attributesToRenameList) {
//            $attributes = $part->getAttributes();
//
//            // Remove unwanted attributes
//            $attributes = array_diff_key($attributes, array_flip($attributesToRemove));
//
//            // Insert new fields with textual values and remove old
//            foreach ($attributesToRenameAndChangeValues as $key => $value) {
//                $attributes[$value] = $attributesToRenameList[$value][$attributes[$key]];
//                unset($attributes[$key]);
//            }
//
////            dd($attributes);
//
//
//
//            $part->setRawAttributes($attributes, true);
//            return $part;
//        });
//dd($suppliersParts);
//
//        return $suppliersParts;
//
//    }

//    public function getList($attribute)
//    {
//        $className = '\App\Models\\' . ucfirst($attribute);
//        $attributesRaw = $className::all()->toArray();
//
//        $attributesList = [];
//        foreach ($attributesRaw as $attributeRaw) {
//            $attributesList[$attributeRaw['id']] = $attributeRaw['name'];
//        }
//
//        return $attributesList;
//    }

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
        if (!empty($supplier_id)) {
            $query->whereIn('parts.supplier_id', $supplier_id);
        }
    }


}
