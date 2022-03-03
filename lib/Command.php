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
    
}