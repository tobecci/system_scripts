<?php

namespace Tobecci\Modules;
include_once __DIR__.'/../lib/Command.php';
include_once __DIR__.'/../lib/Menu.php';
class Scrcpy
{
    private $cmd;
    private $menu;
    private $scrcpy_start_command = "scrcpy -m1024";
    private $kill_sever_command = "adb kill-server";

    public function __construct()
    {
        // error_reporting(0);
        $this->cmd = new \Tobecci\Libs\Command();    
        $this->menu = new \Tobecci\Libs\Menu();
    }

    public function start()
    {
        if(!$this->kill_adb_sever()) return false;
        $ip_address =  $this->get_wifi_ip();
        $port = "5555";
        if(!$ip_address) return false;
        echo("$ip_address:$port\n");
        if(!$this->start_adb_in_tcpip_mode($port)) return false;
        if(!$this->connect_adb($ip_address, $port)) return false;
        echo("UNPLUG YOUR DEVICE\n");
        sleep(5);
        if(!$this->start_scrcpy()) return false;
        return true;
    }

    public function get_wifi_ip()
    {
        echo("*** getting ip** \n");
        $command = "ip route";
        $result = $this->cmd->run_command($command);
        if($result){
            // echo "it has started";
            preg_match('/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/', $result[0], $matches);
            return $matches[0];
        }
        echo("CONNECT TO THE WIFI\n");
        return false;
    }

    public function start_adb_in_tcpip_mode($port)
    {
        echo("*** starting in tcpip **  \n");
        $command = "adb tcpip $port";
        $result = $this->cmd->run_command($command);
        if(!$result) return false;
        return true;
    }

    public function connect_adb($ip_address, $port)
    {
        echo("*** connecting adb server** \n");
        $command = "adb connect $ip_address:$port";
        // echo("$command\n");
        $result = $this->cmd->run_command($command);
        if(!$result) return false;
        return true;
    }

    public function start_scrcpy()
    {
        echo("*** starting scrcpy ***\n");
        $result = $this->cmd->run_command($this->scrcpy_start_command);
        if(!$result) return false;
        return true;
    }

    public function kill_adb_sever()
    {
        echo("*** killing adb server** \n");
        $code = false;
        $result = $this->cmd->run_command($this->kill_sever_command, $code);
        if(!$code) return false;
        return true;
    }
}