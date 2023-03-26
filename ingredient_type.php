<?php

class IngredientType
{
    private int $_id;
    private string $_title;
    private string $_code;

    public function __construct(int $id, string $title, string $code)
    {
        $this->_id = $id;
        $this->_title = $title;
        $this->_code = $code;
    }

    public function GetId() : int
    {
        return $this->_id;
    }

    public function GetTitle() : string
    {
        return $this->_title;
    }

    public function GetCode() : string
    {
        return $this->_code;
    }
}