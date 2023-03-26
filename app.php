<?php

include 'dish.php';
// declare(strict_types=1);

class App
{
    private mysqli $_mysqli;
    private array $_ingredient_data = [];
    private array $_dish_data = [];
    private array $_set;

    private static $_app;

    private function __construct(array $config)
    {
        $this->_mysqli = new mysqli($config["server"], $config["username"], $config["password"], $config["db"]);

        if ($this->_mysqli->connect_error) {
            throw new RuntimeException("Db connection error");
        };
    }

    public static function Init(array $config) : App
    {
        if(self::$_app === null) {
            self::$_app = new self($config);
        }

        return self::$_app;
    }

    public function FillIngredientData(string $set_str) : App
    {
        $this->_set = str_split($set_str);
        $map_data = [];
        $count_map = [];
        
        $counter = 1;
        foreach($this->_set as $key => $value) {
            !isset($count_map[$value]["item_count"]) ? $count_map[$value]["item_count"] = 1 : $count_map[$value]["item_count"]++;
            $count_map[$value]["current_count"] = $counter++;
            $count_map[$value]["prev_count"] = $count_map[$value]["current_count"] - $count_map[$value]["item_count"];
        }

        $params = join("','", array_keys($count_map));

        // Получаем все ингредиенты, которые могут удоавлетворять запросу.
        $result = $this->_mysqli->query("SELECT i.id, i.title, i.price, i.type_id, t.title AS type_title, t.code 
            FROM ingredient i LEFT JOIN ingredient_type t ON i.type_id=t.id WHERE t.code IN ('$params')");

        while ($row = $result->fetch_assoc()) {
             $this->_ingredient_data[$row["id"]] = $row;
             $map_data[$row["code"]][] = $row;
        }

        $this->_dish_data = $this->DishGenerate($map_data, $count_map, count($this->_set));

        return $this;
    }

    public function Print()
    {
        print_r(json_encode($this->_dish_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    private function DishGenerate(array $map_data, array $count_map, int $length, array $result = [], 
                                    string $ingrs = "", int $a = 0, int $b = 0, int $c = 0) : array
    {
        if(strlen($ingrs) === $length) {
            $ingrs_data = [];

            foreach(str_split($ingrs) as $key => $item) {
                $ingrs_data[] = new Ingredient($this->_ingredient_data[$item]);
            }

            $dish = new Dish($ingrs_data);
            $result[] = $dish->AsArray();
        } else {
            foreach($map_data as $key => $sub_arr) {
                for($i = 0; $i < count($sub_arr); ++$i) {
                    if($ingrs !== $sub_arr[$i]["id"] && !strpos($ingrs, $sub_arr[$i]["id"]) && strlen($ingrs) < $count_map[$key]["current_count"] &&
                            strlen($ingrs) >= $count_map[$key]["prev_count"]) {

                        if($key === "d"  && ($a === 0 || $sub_arr[$i]["id"] > substr($ingrs, -1)) && $count_map[$key]["item_count"] > $a) {
                            $result = $this->DishGenerate($map_data, $count_map, $length, $result, $ingrs . $sub_arr[$i]["id"], $a + 1, $b, $c);
                        }

                        if($key === "c"  && ($b === 0 || $sub_arr[$i]["id"] > substr($ingrs, -1)) && $count_map[$key]["item_count"] > $b) {
                            $result = $this->DishGenerate($map_data, $count_map, $length, $result, $ingrs . $sub_arr[$i]["id"], $a, $b + 1, $c);
                        }

                        if($key === "i" && ($c === 0 || $sub_arr[$i]["id"] > substr($ingrs, -1)) && $count_map[$key]["item_count"] > $c) {
                            $result = $this->DishGenerate($map_data, $count_map, $length, $result, $ingrs . $sub_arr[$i]["id"], $a, $b, $c + 1);
                        }

                    }
                }
            }
        }

        return $result;
    }

    private function __clone() 
    {

    }

    private function __wakeup() 
    {

    }    

}