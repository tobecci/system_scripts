<?php

/**
 * @author Tochukwu Ojianka <ojinakatochukwu@gmail.com>
 * Handles execution of shell commands
 */

namespace Tobecci\Libs;

class Command
{
    private $clear_command = "clear";

    public function __construct()
    {
        
    }

    public function clear_screen()
    {
        echo shell_exec($this->clear_command);   
    }

    public function run_command($command, &$code=false)
    {
        // surpresses error messages displayed on the terminal
        $output = [];
        $result_code = null;
        exec($command, $output, $result_code);
        if($result_code === 0)
        {
            $code = true;
            return $output;
        }
        return false;
    }
    
}