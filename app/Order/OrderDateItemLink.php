<?php

namespace App\Order;

class OrderDateItemLink extends \App\BaseModel
{
    protected $with = ['orderItemRelationship', 'orderDateRelationship'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'user_id' => 'required',
        'producer_id' => 'required',
        'order_item_id' => 'required',
        'order_date_id' => 'required',
        'quantity' => 'required',
        'ref' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'producer_id',
        'order_item_id',
        'order_date_id',
        'quantity',
        'ref',
    ];

    /**
     * Define relationship with order items.
     *
     * @return Collection
     */
    public function orderItemRelationship()
    {
        return $this->hasMany('App\Order\OrderItem', 'id', 'order_item_id');
    }

    /**
     * Get order item.
     *
     * @return OrderItem
     */
    public function getItem()
    {
        return $this->orderItemRelationship->first();
    }

    /**
     * Define relationship with order dates.
     *
     * @return OrderDate
     */
    public function orderDateRelationship()
    {
        return $this->hasMany('App\Order\OrderDate', 'id', 'order_date_id');
    }

    /**
     * Get order date.
     *
     * @return OrderDate
     */
    public function getDate()
    {
        return $this->orderDateRelationship->first();
    }

    /**
    * Get item price.
    *
    * @return int
    */
    public function getPrice()
    {
        $price = $this->getItem()->variant ?  $this->getItem()->variant['price'] : $this->getItem()->product['price'];

        if ($this->getItem()->product['price_unit'] === 'product') {
            // Sold by product
            return $price * $this->quantity;
        } else {
            // Sold by weight
            if ($this->getItem()->variant) {
                return $price * $this->quantity * $this->getItem()->variant['package_amount'];
            } else {
                return $price * $this->quantity * $this->getItem()->product['package_amount'];
            }
        }
    }

    /**
     * Get unit.
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->getItem()->producer['currency'];
    }

    /**
     * Get price unit.
     *
     * @return string
     */
    public function getPriceWithUnit()
    {
        $prefix = '';
        if ($this->getItem()->product['price_unit'] !== 'product') {
            $prefix = '<span class="approx">&asymp;</span>';
        }

        return $prefix . ' ' . $this->getPrice() . ' ' . $this->getUnit();
    }
}
