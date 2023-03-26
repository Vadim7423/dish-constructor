<?php

include 'ingredient_type.php';

class Ingredient
{
    private int $_id;
    private string $_title;
    private float $_price;
    private IngredientType $_type;

    public function __construct(array $params)
    {
        $this->_id = $params["id"] ?? null;
        $this->_title = $params["title"]  ?? null;
        $this->_price = $params["price"] ?? null;
        $this->_type = new IngredientType($params["type_id"] ?? null,
                                          $params["type_title"] ?? null,
                                          $params["code"] ?? null);
    }

    public function GetId() : int
    {
        return $this->_id;
    }

    public function GetTitle() : string
    {
        return $this->_title;
    }

    public function GetPrice() : float
    {
        return $this->_price;
    }

    public function GetType() : IngredientType
    {
        return $this->_type;
    }
    

}