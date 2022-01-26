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

    public function getPartsForCsvDownloadFile($supplierId)
    {
        // # Get supplier parts (collection) from DB
        $suppliersPartsRaw = Part::where('supplier_id', $supplierId)->get();

        // # Define which attributes are to be: removed, renamed+values changed
        $attributesToRemove = ['id', 'supplier_id'];

        $attributesToRenameAndChangeValues = [ // Works if attributes base model has: "id" & "name" attributes
            'condition_id' => 'condition',
            'category_id' => 'category'
        ];

        // Create usable list of attributes to be changed
        $attributesToRenameList = [];
        foreach ($attributesToRenameAndChangeValues as $attribute) {
            $attributesToRenameList[$attribute] = $this->getList($attribute);
        }

        $suppliersPartsRaw->transform(function (Part $part, $key) use ($attributesToRemove, $attributesToRenameAndChangeValues, $attributesToRenameList) {
            $attributes = $part->getAttributes();

            // Remove unwanted attributes
            $attributes = array_diff_key($attributes, array_flip($attributesToRemove));
//            dd($attributes);

            // Insert new fields with textual values and remove old
            foreach ($attributesToRenameAndChangeValues as $key => $value) {
                $attributes[$value] = $attributesToRenameList[$value][$attributes[$key]];
                unset($attributes[$key]);
            }

            dd($attributes);


            // Rename attributes


        });


//        $suppliersParts->transform(function (Part $part, $key) use ($attributesToRemove, $attributesToRenameAndConvertValues, $conditionList, $categoryList) {
//            $attributes = $part->getAttributes();
//
//            // Remove unwanted attributes
//            $attributes = array_diff_key($attributes, array_flip($attributesToRemove));
////            dd($attributes);
//
//            // Insert new fields with textual values and remove old
//            foreach ($attributesToRenameAndConvertValues as $key => $value) {
//                $attributes[$value] = $conditionList[$attributes[$key]];
//                unset($attributes[$key]);
//            }
//
////            $attributes['condition'] = $conditionList[$attributes['condition_id']];
////            unset($attributes['condition_id']);
////
////            $attributes['category'] = $categoryList[$attributes['category_id']];
////            unset($attributes['category_id']);
//
//            dd($attributes);
//
//
//            // Rename attributes
//
//
//        });

    }

    public function getList($attribute)
    {
        $className = '\App\Models\\' . ucfirst($attribute);
        $attributesRaw = $className::all()->toArray();

//        dd($conditionsRaw);

        $attributesList = [];
        foreach ($attributesRaw as $attributeRaw) {
            $attributesList[$attributeRaw['id']] = $attributeRaw['name'];
        }


        return $attributesList;
    }

//    public function getCategoryList()
//    {
//        $categoriesRaw = Category::all()->toArray();
//
//
//        $categoryList = [];
//        foreach ($categoriesRaw as $categoryRaw) {
//
//            $categoryList[$categoryRaw['id']] = $categoryRaw['name'];
//        }
//
//        return $categoryList;
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
