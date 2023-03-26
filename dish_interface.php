<?php

interface DishInterface
{
    public function GetProducts() : array;
    public function GetPrice() : float;
    public function AsArray() : array;
}