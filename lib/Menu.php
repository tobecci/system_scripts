<?php

namespace Tobecci\Libs;

include_once __DIR__ . "/Command.php";
include_once __DIR__ . "/../modules/Scrcpy.php";
class Menu
{
	private $menu_list = [];
	private $cmd;
	private $commands = array(
		"show_network_devices" => "ip -brief addr show",
	);

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
		if (!$scrcpy->start()) {
			$this->start();
		}

	}

	public function start_shizuku()
	{
		$command = "adb shell sh /storage/emulated/0/Android/data/moe.shizuku.privileged.api/start.sh";
		$this->cmd->run_command($command);
	}

	public function show_all_network_devices()
	{
		$command = $this->commands["show_network_devices"];
		$this->cmd->run_command($command, true);
	}

	public function get_ip_address_for_ftp()
	{
		$wifi_interface_name = "wlo1";
		$command             = "{$this->commands['show_network_devices']} | grep -i {$wifi_interface_name}";
		$command_result      = $this->cmd->run_command($command);
		$matches             = array();
		$ipaddress_regexp    = '/([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/';
		preg_match($ipaddress_regexp, $command_result[0], $matches);
		echo "\n\nIP: $matches[0]\n\n";
	}

	public function adb_list_packages()
	{
		$command = "adb shell 'pm list packages'";
		$this->cmd->run_command($command, true);
	}

	public function adb_list_packages_with_filter()
	{
		echo "input search string:";
		$filter  = (string) fgets(STDIN);
		$command = "adb shell 'pm list packages' | grep $filter";
		$this->cmd->run_command($command, true);
	}

	public function adb_uninstall_system_app()
	{
		echo "input package name:";
		$package_name = (string) fgets(STDIN);
		$command      = "adb shell 'pm uninstall -k --user 0 $package_name'";
		echo $command;
		$this->cmd->run_command($command, true);
	}

	public function adb_REINSTALL_system_app()
	{
		echo "input package name:";
		$package_name = (string) fgets(STDIN);
		$command      = "adb shell 'pm install-existing $package_name'";
		echo $command;
		$this->cmd->run_command($command, true);
	}

	public function adb_REINSTALL_system_app_second_method()
	{
		echo "input package name:";
		$package_name        = (string) fgets(STDIN);
		$package_name        = substr($package_name, 0, strlen($package_name) - 1);
		$command_to_get_path = "adb shell pm dump $package_name | grep path";
		$path_command_result = $this->cmd->run_command($command_to_get_path);
		$path_command_result = $path_command_result[1];

		$matches         = array();
		$apk_path_regexp = '/\/.*\.apk/';
		preg_match($apk_path_regexp, $path_command_result, $matches);
		$apk_path = $matches[0];

		$command_to_reinstall_system_app = "adb shell pm install -r --user 0 $apk_path";
		$this->cmd->run_command($command_to_reinstall_system_app, true);
	}

	public function output_to_ixio()
	{
		echo 'input command:';
		$linux_command   = (string) fgets(STDIN);
		$trimmed_command = rtrim($linux_command, '\n');
		$linux_command   = substr($linux_command, 0, strlen($linux_command) - 1);
		$command         = "$linux_command | curl -F 'f:1=<-' ix.io";
		echo $command;
		echo shell_exec($command);
	}

	public function install_xfce()
	{
		$command_to_install_new_de              = "sudo pacman -S xfce4 xfce4-goodies pavucontrol blueman --noconfirm";
		$command_to_enable_display_manager      = "sudo systemctl enable lightdm --force";
		$command_to_remove_old_kde              = "sudo pacman -Rcnc plasma kde-applications --noconfirm";
		$command_to_remove_old_gnome            = "sudo pacman -Rcnc gnome gnome-extra --noconfirm";
		$command_to_set_env_file                = "sudo cp /etc/environment-xfce /etc/environment";
		$command_to_configure_libinput_gestures = "cp /home/tobecci/.config/libinput-gestures-xfce.conf /home/tobecci/.config/libinput-gestures.conf";
		$command_to_reboot                      = "reboot";
		$command                                =
			"$command_to_install_new_de ;" .
			"$command_to_enable_display_manager;" .
			"$command_to_remove_old_kde;" .
			"$command_to_remove_old_gnome;" .
			"$command_to_set_env_file;" .
			"$command_to_reboot";
		shell_exec($command);
		shell_exec($command_to_configure_libinput_gestures);
	}

	public function install_kde()
	{
		$command_to_install_new_de              = "sudo pacman -S plasma kde-applications --noconfirm";
		$command_to_enable_display_manager      = "sudo systemctl enable sddm --force";
		$command_to_remove_old_xfce             = "sudo pacman -Rcnc xfce4 xfce4-goodies pavucontrol blueman --noconfirm";
		$command_to_remove_old_gnome            = "sudo pacman -Rcnc gnome gnome-extra --noconfirm";
		$command_to_set_env_file                = "sudo cp /etc/environment-kde /etc/environment";
		$command_to_configure_libinput_gestures = "cp /home/tobecci/.config/libinput-gestures-kde.conf /home/tobecci/.config/libinput-gestures.conf";
		$command_to_reboot                      = "reboot";
		$command                                =
			"$command_to_install_new_de;" .
			"$command_to_enable_display_manager;" .
			"$command_to_remove_old_xfce;" .
			"$command_to_remove_old_gnome;" .
			"$command_to_set_env_file;" .
			"$command_to_reboot";
		shell_exec($command);
		shell_exec($command_to_configure_libinput_gestures);
	}

	public function install_gnome()
	{
		$command_to_install_new_de              = "sudo pacman -S gnome gnome-extra --noconfirm";
		$command_to_enable_display_manager      = "sudo systemctl enable gdm --force";
		$command_to_remove_old_xfce             = "sudo pacman -Rcnc xfce4 xfce4-goodies pavucontrol blueman --noconfirm";
		$command_to_remove_old_kde              = "sudo pacman -Rcnc plasma kde-applications --noconfirm";
		$command_to_set_env_file                = "sudo cp /etc/environment-gnome /etc/environment";
		$command_to_configure_libinput_gestures = "cp /home/tobecci/.config/libinput-gestures-gnome.conf /home/tobecci/.config/libinput-gestures.conf";
		$command_to_reboot                      = "reboot";
		$command                                =
			"$command_to_install_new_de;" .
			"$command_to_enable_display_manager;" .
			"$command_to_remove_old_xfce;" .
			"$command_to_remove_old_kde;" .
			"$command_to_set_env_file;" .
			"$command_to_reboot";
		shell_exec($command);
		shell_exec($command_to_configure_libinput_gestures);
	}

	function exit()
	{
		die();
	}

	public function generate_menu()
	{
		$i             = 1;
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
		foreach ($this->menu_list as $key => $menu_item) {
			echo "[$key] $menu_item\n";
		}
		echo "selection: ";
	}

	public function start()
	{
		try {
			$this->display_menu();
			$menu_selection  = (integer) fgets(STDIN);
			$function_to_run = $this->menu_list[$menu_selection];
			if ($function_to_run === null) {
				throw new \Exception();
			}

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