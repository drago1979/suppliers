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
        $suppliersParts = Part::where('supplier_id', $supplierId)->get();

        $attributesToRemove = ['id', 'supplier_id'];

        $conditionsList = $this->getConditionsIdAndText();

        $categoriesList = $this->getCategoriesIdAndText();

        $attributesToRenameAndConvertValues = [
            'condition_id' => 'condition',
            'category_id' => 'category'
        ];

        $suppliersParts->transform(function (Part $part, $key) use ($attributesToRemove, $attributesToRenameAndConvertValues, $conditionsList, $categoriesList) {
            $attributes = $part->getAttributes();

            // Remove unwanted attributes
            $attributes = array_diff_key($attributes, array_flip($attributesToRemove));
//            dd($attributes);

            // Insert new fields with textual values and remove old
            foreach ($attributesToRenameAndConvertValues as $key => $value) {
                $attributes[$value] = $conditionsList[$attributes[$key]];
                unset($attributes[$key]);
            }

//            $attributes['condition'] = $conditionsList[$attributes['condition_id']];
//            unset($attributes['condition_id']);
//
//            $attributes['category'] = $categoriesList[$attributes['category_id']];
//            unset($attributes['category_id']);

            dd($attributes);


            // Rename attributes


        });

    }

    public function getConditionsIdAndText()
    {
        $conditionsRaw = Condition::all()->toArray();

        $conditionsList = [];
        foreach ($conditionsRaw as $conditionRaw) {
            $conditionsList[$conditionRaw['id']] = $conditionRaw['name'];
        }

        return $conditionsList;
    }

    public function getCategoriesIdAndText()
    {
        $categoriesRaw = Category::all()->toArray();


        $categoriesList = [];
        foreach ($categoriesRaw as $categoryRaw) {

            $categoriesList[$categoryRaw['id']] = $categoryRaw['name'];
        }

        return $categoriesList;
    }


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
