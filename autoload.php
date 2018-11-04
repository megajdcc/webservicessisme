
<?php 
	
	function cargar($class){
		include 'Model/'.$class.'.php';
	}

	spl_autoload_register('cargar');

 ?>