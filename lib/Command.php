<?php

/**
 * @author Tochukwu Ojianka <ojinakatochukwu@gmail.com>
 * Handles execution of shell commands
 */

namespace Tobecci\Libs;

class Command
{
    private $linux_clear_command = "clear";
    private $win_clear_command = "cls";
    private $os = "";

    public function __construct()
    {
        $this->os = strtolower(PHP_OS);
    }

    public function clear_screen()
    {
        if ($this->os === "linux") {
            echo shell_exec($this->linux_clear_command);
            return;
        }
        shell_exec($this->win_clear_command);
        return;
    }

    public function run_command($command, $print_result = false, &$code = false, )
    {
        // surpresses error messages displayed on the terminal
        $output      = [];
        $result_code = null;
        exec($command, $output, $result_code);
        if ($print_result)
            print_r($output);
        if ($result_code === 0) {
            $code = true;
            return $output;
        }
        return false;
    }

}