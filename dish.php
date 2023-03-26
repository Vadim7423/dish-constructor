<?php

include 'dish_interface.php';
include 'ingredient.php';

class Dish implements DishInterface
{
    private array $_products = [];
    private float $_price = 0;

    public function __construct(array $products)
    {
        $this->_products = $products;

        foreach($products as $key => $value) {
            $this->_price += $value->GetPrice();
        }
    }

    public function GetProducts() : array
    {
        return $this->_products;
    }

    public function GetPrice() : float
    {
        return $this->_price;
    }

    public function AsArray() : array
    {
        $products = [];

        foreach($this->_products as $key => $value) {
            $products[] = [
                "type" => $value->GetType()->GetTitle(),
                "value" => $value->GetTitle()
            ];
        }

        return [
            "products" => $products,
            "price" => $this->_price
        ];
    }
}