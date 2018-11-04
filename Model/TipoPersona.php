<?php 
namespace modelo;
include 'autoload.php';

use modelo\Conexion;
use modelo\TipoPersona;
/**
* @autor Sigprome... 
*/
class TipoPersona{
	private $conexion;
	function __construct($Person){
		$this->Consultar($Person);
	}

	//Movimiento..
	public function Consultar($person){
		$conexion = $this->conexion = new Conexion();
		$param = array('persona' => $person );
		$conexion->beginTransaction();
		$sql = ' SELECT tipopersona.nombre as tipoperpersona from tipopersona inner join persona on(tipopersona.id = persona.id_tipopersona) '.'where persona.cedula = :cedulapersona';
 		$stm = $conexion->prepare($sql);
 		$stm->execute($param);
 		$dates = $stm->fetchAll(PDO::FETCH_BOTH);
 		echo $dates['tipopersona'];
		// $this->json = $dates['datos'][] = $datos;
		// echo json_encode($this->json);
		$stm->closeCursor();		
	}


	//Campos o fiels ... 
	
	private $id, $nombre;
}

 ?>