<?php

namespace App\Models;

use App\Facades\Stripe;
use App\Models\DrugClass;
use App\Models\MedicineForm;
use App\Models\MedicineRoute;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Medicine extends Model
{
    use CrudTrait;

    protected static function booted()
    {
        static::creating(function ($medicine) {
            $product = Stripe::createProduct($medicine->name);
            $medicine->stripe_product_id = $product->id;
            
            $price = Stripe::createPrice($product->id, $medicine->price);
            $medicine->stripe_price_id = $price->id;
        });

        static::updating(function ($medicine) {
            $product = Stripe::updateProduct($medicine->stripe_product_id, $medicine->name);
            $medicine->stripe_product_id = $product->id;

            Stripe::deactivatePrice($medicine->stripe_price_id);
            $price = Stripe::createPrice($product->id, $medicine->price);
            $medicine->stripe_price_id = $price->id;
        });

        static::deleting(function ($medicine) {
            Stripe::deactivateProduct($medicine->stripe_product_id);
            Stripe::deactivatePrice($medicine->stripe_price_id);
        });
    }

    protected $fillable = [
        'name',
        'generic_name',
        'drug_class_id',
        'medicine_form_id',
        'strength',
        'medicine_route_id',
        'unit',
        'stock',
        'price',
        'batch_number',
        'expiry_date',
        'manufacturer',
        'stripe_product_id',
        'stripe_price_id'
    ];

    public function drugClass()
    {
        return $this->belongsTo(DrugClass::class);
    }

    public function medicineForm()
    {
        return $this->belongsTo(MedicineForm::class);
    }

    public function medicineRoute()
    {
        return $this->belongsTo(MedicineRoute::class);
    }
}
