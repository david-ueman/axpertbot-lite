<?php
	echo "Hi! this is $_SERVER[REMOTE_ADDR]..." . PHP_EOL;

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

    require 'database_link.php';

	echo "Trying to open a connection to the database...";	
	if (!$link) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	else
	{
		echo "Connected..." . PHP_EOL;
		
		//Forcing to cast each value to a numeric type
		//so that we can erase the leading zeros or
		//any other character that prevents the database
		//to understand the values as numeric values
		foreach ($_POST as &$value) {
			$value = str_replace ("'", "", $value);
			$value = floatval ($value);
		}
		
		$sqlInsertQuery = "INSERT INTO `eslaekomedidas`.`luzparaeleko_inverter_data` (
				`ac_output_voltage`, 
				`ac_output_frequency`, 
				`ac_output_apparent_power`, 
				`ac_output_active_power`,
				`output_load_percent`,
				`bus_voltage`,
				`battery_voltage`,
				`battery_charging_current`,
				`battery_capacity`,
				`inverter_heat_sink_temperature`,
				`network_id`
			)
			VALUES (" . 
				$_POST['AC_output_voltage'] . "," .
				$_POST['AC_output_frequency'] . "," .
				$_POST['AC_output_apparent_power'] . "," .
				$_POST['AC_output_active_power'] . "," .
				$_POST['Output_load_percent'] . "," .
				$_POST['BUS_voltage'] . "," .
				$_POST['Battery_voltage'] . "," .
				$_POST['Battery_charging_current'] . "," .
				$_POST['Battery_capacity'] . "," .
				$_POST['Inverter_heat_sink_temperature'] . "," .
                $_POST['Inverter_serial_number'] . ");";
	
		if (mysqli_query($link, $sqlInsertQuery))
		{
			echo "The query was executed successfully!" . PHP_EOL;
		} else {
			echo "The query resulted in ERROR :(," . PHP_EOL;
			echo "full SQL query: $sqlInsertQuery" . PHP_EOL;
			echo mysqli_error($link) . PHP_EOL;
		}

		echo "Closing connection to database..." . PHP_EOL;
		mysqli_close($link);
	}

?>
