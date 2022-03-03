<?php

namespace Tobecci\Libs;
include_once  __DIR__."/Command.php";
include_once  __DIR__."/../modules/Scrcpy.php";
class Menu
{
    private $menu_list = [];
    private $cmd;

    public function __construct()
    {
        // error_reporting(0);
        $this->cmd = new \Tobecci\Libs\Command();
        $this->generate_menu();
    }

    public function run_phpmyadmin()
    {
        echo "running phpmyadmin\n";
    }

    public function launch_scrcpy_on_wifi()
    {
        $scrcpy = new \Tobecci\Modules\Scrcpy();
        if(!$scrcpy->start()) $this->start();
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
        echo "\n";
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
            $function_to_run = $this->menu_list[$menu_selection];
            if( $function_to_run === NULL) throw new \Exception();
            $this->cmd->clear_screen();
            $this->$function_to_run();
            $this->start();
        } catch (\Exception $e) {
            $this->cmd->clear_screen();
            echo "Invalid Input\n";
            $this->start();
        }
    }
}
