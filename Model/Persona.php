<?php 
// require_once 'Conexion.php';


	/**
	* @autor Jhonatan Crespo
	*/
	class Persona 
	{
		
		// Metodos
		public function consultar(){
			echo 'hola hola';
		} 
		
		//  Campo de clases  
		private $nombre,$apellido,$cedula,$fechanacimiento,$telefono,$direccion,$genero,$correo;
		private $id;

		
	public function modificarperson($cedula,$telefono,$correo){
		
		$conexion = new Conexion();
		$this->correo = $correo;
		$this->cedula = $cedula;
		$this->telefono = $telefono;
		$datos = array('cedula'=>$this->cedula, 'telefono'=>$this->telefono);
		$datos1 = array('cedula'=>$this->cedula,'email'=> $this->correo);
		$conexion->beginTransaction();

		$sql = "UPDATE persona set telefono =:telefono,actualizado = false where cedula=:cedula";
		$sql1 = "UPDATE usuario set email = :email, sincronizado = false where cedulapersona = :cedula";

		try {
			$prepar = $conexion->prepare($sql);
			$preparacion  = $conexion->prepare($sql1);
		
		// $prepar->bindParam(':telefono', $this->telefono,PDO::PARAM_INT);
		// $prepar->bindParam(':cedula', $this->cedula, PDO::PARAM_INT);
		$result = $prepar->execute($datos);
		$resulta = $preparacion->execute($datos1);

		$res = $preparacion->rowCount();
		// $resultado1 = $conexion->commit();

		$result = $prepar->rowCount();
		$resultado = $conexion->commit();
			if($resulta and $result){
				$exito = array('exito' =>true );
				echo json_encode($exito);
			}else{
				$exito = array('exito' =>false );
				echo json_encode($exito);
			}
		} catch (PDOException $e) {
			$this->conexion->rollback();
			echo $e;
		}
	
	}
}

 ?>