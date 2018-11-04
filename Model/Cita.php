<?php 
require_once('autoload_model.php');


/**
 * @author Crespo jhonatan...
 */
class Cita{
	private $conexion;

	function __construct(){
		$this->conexion = new Conexion();

	}

	public function consultar($cedula){

		$dato = array(':cedula' => $cedula);
		$sql = "select c.id, c.fecha from cita as c join servicio as s on c.id = s.id_cita	
				join usuario as u on s.id_usuario = u.id	
					where u.cedulapersona = :cedula and c.procesada = 0 ";

		$stm = $this->conexion->prepare($sql);

		$result = $stm->execute($dato);

		if($result){
			$dates = $stm->fetchALL(PDO::FETCH_ASSOC);
			if(empty($dates)){
						// $datees['datos'] = false;
						// echo json_encode($datees);
					}else{
						$datees['datos'] = $dates;
						echo json_encode($datees);
					}
		}else{
			
		}

	}

	public function verificar($fecha){

		$dato =  array(':fecha' => $fecha );

		$sql = "select count(c.fecha) as cuenta from cita as c where c.fecha = :fecha";

		$stm = $this->conexion->prepare($sql);
		$result = $stm->execute($dato);

		if($result){

			$datos = $stm->fetchALL(PDO::FETCH_ASSOC);
			$datees['datos'] =$datos;
			echo json_encode($datees);
		}

	}



	public function asignar($cedula,$fecha){

		$datos = array(':cedula'=>$cedula);

		$idusuario;
		try {
			$sql = "select u.id as id from usuario as u where u.cedulapersona = :cedula";

		$stm = $this->conexion->prepare($sql);

		$result = $stm->execute($datos);

		if($result){
			 $id = $stm->fetch(PDO::FETCH_ASSOC);

			 $idusuario = $id['id'];

			 $sql4 = "select max(id) as id from cita";

			 $stmm = $this->conexion->prepare($sql4);
			 $stmm->execute();

			 $id1 = $stmm->fetch(PDO::FETCH_ASSOC);
			 $idcita = $id1['id'];
			 $this->conexion->beginTransaction();

			 $idcita++;
			 $dato = array(':id'=>$idcita,':fecha'=>$fecha, ':procesada' => false, ':sincronizado' => false);

			 $sql1 = "insert into cita(id,fecha,procesada,sincronizado) values(:id,:fecha,:procesada,:sincronizado)";

			 $stm = $this->conexion->prepare($sql1);
			 $result = $stm->execute($dato);

			 if($result){
			 	$sql2 = "SELECT max(id) as id from cita";
			 	$sstm = $this->conexion->prepare($sql2);

			 	$resultado = $sstm->execute();
			 	if($resultado){
			 		 $resultado1 = $sstm->fetch(PDO::FETCH_ASSOC);

			 		 $cita = $resultado1['id'];

			 		 $sql5 = "select max(id) as id from servicio";

			 		 $sta = $this->conexion->prepare($sql5);
			 		 $sta->execute();

			 		 $id3 = $sta->fetch(PDO::FETCH_ASSOC);

			 		 $idservi = $id3['id'];

			 		$sql3 = "insert into servicio(id,fecha,id_cita,id_tiposervicio,id_usuario,procesado,sincronizado) values(:id,:fecha,:cita,6,:usuario,0,0)";

			 		$stm1 = $this->conexion->prepare($sql3);

			 		$resultado1 = $stm1->execute(array(
			 			':fecha' => date('Y-m-d'),
			 			':cita' => $cita,
			 			':usuario' => $idusuario,
			 			':id'=>$idservi + 1));

			 		if($resultado1){
			 			$this->conexion->commit();

			 			$datos1['datos'][] = array('registrado'=>true);

			 			echo json_encode($datos1);
			 		}else{
			 			$this->conexion->rollback();
			 			
			 			$datos1['datos'][] = array('registrado'=>false);

			 			echo json_encode($datos1);
			 		}
			 	}
			 } 
			 }

		} catch (PDOException $e) {
			$this->conexion->rollback();
			echo $e->getMessage();
		}
			
		}

		public function listar($cedula){

			$sql ="select c.fecha, c.procesada from cita as c join servicio as s on c.id = s.id_cita 
				join usuario as u on s.id_usuario = u.id
				where u.cedulapersona = :cedula";

			$stm = $this->conexion->prepare($sql);
			$result = $stm->execute(array(':cedula'=>$cedula));

			if($result){
				$datees = $stm->fetchALL(PDO::FETCH_ASSOC);
				if($datees){
						$datos['datos'] = $datees;
						echo json_encode($datos);
				}else{
					// 	$datos['datos'][] = array('existe' => false);
					// 	echo json_encode($datos);
					// // echo "no hay";
				}
			//	echo var_dump($datees);
			
			}


		}

	public function posponer($id,$fecha){

		$this->conexion->beginTransaction();
		try {
			$sql = "update cita set fecha = :fecha where id = :id";

		$stm = $this->conexion->prepare($sql);

		$result = $stm->execute(array(':fecha' =>$fecha,':id' => $id));

		if($result){
			$this->conexion->commit();
			$datos['datos'][]= array('pospuesto'=>true);
			echo json_encode($datos);
		}else{
			$this->conexion->rollback();
			$datos['datos'][]= array('pospuesto'=>false);
			echo json_encode($datos);
		}
		} catch (PDOException $e) {
			$this->conexion->rollBack();
		}
		

	}

}

 ?>