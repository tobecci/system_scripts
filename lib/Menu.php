<?php

namespace Tobecci\Libs;

use Exception;
use Throwable;

class Menu
{
    private $menu_list = [];

    public function __construct()
    {
        $this->generate_menu();
        $this->start();
    }

    public function run_phpmyadmin()
    {
        // var_dump("funct 1 runs");
    }

    public function launch_scrcpy_on_wifi()
    {
        // var_dump("funct 2 runs");
    }

    public function generate_menu()
    {
        $i = 1;
        $class_methods = get_class_methods($this);
        foreach ($class_methods as $key => $function_name) {
            $ignored_functions = ["__construct", "generate_menu", "start", 'display_menu'];
            if (!in_array($function_name, $ignored_functions)) {
                $this->menu_list[$i] = $function_name;
                $i++;
            }
        }
    }

    public function display_menu()
    {
        foreach($this->menu_list as $key => $menu_item)
        {
            echo "[$key] $menu_item\n";
        }
        echo "selection: ";
    }

    public function start()
    {
        try {
            $this->display_menu();
            $menu_selection = (integer) fgets(STDIN);
            var_dump(is_int($menu_selection), $menu_selection);
            echo($this->menu_list[$menu_selection]);
        } catch (Throwable $th) {
            // $this->start();
            // echo $th;
        }
    }
}
