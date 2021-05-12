<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

    require 'database_link.php';

	if (!$link) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	else
	{
		if (isset($_GET) && isset($_GET['network_id']))
		{
			$network_id = $_GET['network_id'];
		}
		else
		{
			$network_id = 1;
		}

		$sqlQuery = "SELECT * FROM `luzparaeleko_inverter_data` WHERE 1 AND network_id = $network_id ORDER BY id DESC LIMIT 1;";
	
		if ($sqlResult = mysqli_query($link, $sqlQuery))
		{
			if ($sqlResult->num_rows > 0){
				$data_array = $sqlResult->fetch_array();
				
				echo	"Voltaje en Red: $data_array[ac_output_voltage] v <br> 
						Frequencia en Red: $data_array[ac_output_frequency] hz<br>
						Potencia Aparente: $data_array[ac_output_apparent_power] w <br>
						Potencia Activa: $data_array[ac_output_active_power] w <br>
						Baterias al: $data_array[battery_capacity] % <br>
						V. Baterías: $data_array[battery_voltage] v <br>
						Corriente de Carga: $data_array[battery_charging_current] Amp <br>
						Temp. Inversor: $data_array[inverter_heat_sink_temperature] ºC <br>
						Última lectura: $data_array[date]";
			} else {
				echo "The query didn't throw any row with data!" . PHP_EOL;
			}	
		} else {
			echo "The query resulted in ERROR :(," . PHP_EOL;
			echo "full SQL query: $sqlQuery" . PHP_EOL;
			echo mysqli_error($link) . PHP_EOL;
		} 
		mysqli_close($link);
	}

?>
