<?php

namespace App\Product;

use App\BaseModel;

class ProductVariant extends BaseModel
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'product_id' => 'required',
        'name' => 'required',
        'price' => '',
        'package_amount' => 'required',
        'main_variant' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'package_amount',
        'main_variant',
    ];

    /**
     * Get product.
     *
     * @return App\Product\Product
     */
    public function getProduct()
    {
        return $this->hasOne('App\Product\Product', 'id', 'product_id')->first();
    }

    /**
     * Get production quantity.
     *
     * @return int
     */
    public function getProductionQuantity()
    {
        $smallestCommonDenominator = $this->getProduct()->getProductionQuantity() / $this->package_amount;
        $quantity = $smallestCommonDenominator * $this->getProduct()->mainVariant()->package_amount;

        return floor($quantity);
    }

    public function getPackageAmountUnit()
    {
        return $this->package_amount . ' ' . trans_choice('units.' . $this->getProduct()->package_unit, $this->package_amount);
    }

    /**
     * Get product variant unit.
     *
     * @return string
     */
    public function getUnit()
    {
        $product = $this->getProduct();
        if ($product->price_unit === 'product') {
            return $product->producer()->currency;
        } else {
            return $product->producer()->currency . ' / ' . $product->package_unit;
        }
    }


    /**
     * Get price with unit.
     *
     * @return string
     */
    public function getPriceWithUnit()
    {
        return $this->price . ' ' . $this->getUnit();
    }

    /**
     * Get info to be stored with order.
     *
     * @return array
     */
    public function getInfoForOrder()
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'price' => $this->price,
            'package_amount' => $this->package_amount,
            'main_variant' => $this->main_variant,
        ];
    }
}
