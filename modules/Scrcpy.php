<?php

namespace Tobecci\Modules;

include_once __DIR__ . '/../lib/Command.php';
include_once __DIR__ . '/../lib/Menu.php';
class Scrcpy
{
    private $cmd;
    private $menu;
    private $scrcpy_start_command = "scrcpy -m1024";
    private $kill_sever_command = "adb kill-server";
    private $show_adb_devices = "adb devices";

    public function __construct()
    {
        // error_reporting(0);
        $this->cmd  = new \Tobecci\Libs\Command();
        $this->menu = new \Tobecci\Libs\Menu();
    }

    public function start()
    {
        if (!$this->kill_adb_sever())
            return false;
        $ip_address = $this->get_wifi_ip();
        // die();
        $port = "5555";
        if (!$ip_address)
            return false;
        echo ("$ip_address:$port\n");
        if (!$this->start_adb_in_tcpip_mode($port))
            return false;
        if (!$this->connect_adb($ip_address, $port))
            return false;
        echo ("UNPLUG YOUR DEVICE\n");
        sleep(5);
        if (!$this->start_scrcpy())
            return false;
        return true;
    }

    public function get_wifi_ip()
    {
        echo ("*** getting ip** \n");
        $os = strtolower(PHP_OS);
        echo ("$os\n");
        $ip = "";
        if ($os === "linux")
            $ip = $this->get_linux_wifi_ip();
        if ($os === "winnt")
            $ip = $this->get_windows_wifi_ip();
        echo ("$ip \n");
        return $ip;
    }

    public function get_linux_wifi_ip()
    {
        $command = "ip route";
        $result  = $this->cmd->run_command($command);
        if ($result) {
            // echo "it has started";
            preg_match('/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/', $result[0], $matches);
            return $matches[0];
        }
        echo ("CONNECT TO THE WIFI\n");
    }

    public function get_windows_wifi_ip()
    {
        echo ("finding ip address in windows\n");
        $command = "ipconfig";
        $result  = $this->cmd->run_command($command);
        if ($result) {
            $ip_line = 0;
            for ($i = 0; $i < count($result); $i++) {
                preg_match("/wi-fi/i", $result[$i], $matches);
                if ($matches[0]) {
                    $ip_line = $i + 6;
                    break;
                }
            }
            $ip_line = $result[$ip_line];
            preg_match('/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/', $ip_line, $matches);
            return $matches[0];
        }
        return false;
    }

    public function start_adb_in_tcpip_mode($port)
    {
        echo ("*** starting in tcpip **  \n");
        $command = "adb tcpip $port";
        $result  = $this->cmd->run_command($command);
        if (!$result)
            return false;
        return true;
    }

    public function connect_adb($ip_address, $port)
    {
        echo ("*** connecting adb server** \n");
        $command = "adb connect $ip_address:$port";
        // echo("$command\n");
        $result = $this->cmd->run_command($command);
        if (!$result)
            return false;
        return true;
    }

    public function start_scrcpy()
    {
        echo ("*** starting scrcpy  [command] $this->scrcpy_start_command ***\n");
        $this->display_adb_devices();
        $result = $this->cmd->run_command($this->scrcpy_start_command);
        if (!$result)
            return false;
        return true;
    }

    public function display_adb_devices()
    {
        $this->print_alert("displaying adb devices");
        $result = $this->cmd->run_command(($this->show_adb_devices));
        if (!$result)
            $this->print_alert(json_encode($result));
        return true;
    }

    public function print_alert($msg)
    {
        echo ("**** $msg ***\n");
    }

    public function kill_adb_sever()
    {
        echo ("*** killing adb server** \n");
        $code   = false;
        $result = $this->cmd->run_command($this->kill_sever_command, false, $code);
        if (!$code)
            return false;
        return true;
    }
}