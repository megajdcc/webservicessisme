<?php 
		
	require_once('Model/Conexion.php');
	/**
	* @Description: Esta clase se utiliza para servir como mediador entre las peticiones de nuestra app SIGPROME, 
	* con la base de datos mysql en un servidor remoto...  
	* @autor Crespo jhonatan 
	*/
	class Peticion extends Conexion{
		// CAMPOS DE CLASES 
		 
		private $usuario = 0;
		private $contrasena= "";
		private $json = array();
		private $datees = null;
		function __construct(){
			$this->Conectar();
			if(isset($_GET['us']) && isset($_GET['contra'])){
				$this->usuario = $_GET['us'];
				$this->contrasena = $_GET['contra'];
				settype($this->usuario,"integer");
				$this->loguearse();
			}
		}
		public function loguearse(){
        
				$datos = array('cedula'=>$this->usuario,'contrasena'=>$this->contrasena);
				$this->getConexion()->beginTransaction();// prepara una transaccion ... 
				$sql = "SELECT * from usuario where cedulapersona = :cedula and contrasena = :contrasena";
				$stm = $this->getConexion()->prepare($sql);
				$stm->execute($datos);
				 $dates = $stm->fetchAll(PDO::FETCH_BOTH);
				if($dates){
				    
				   
				$datees['datos'][] = $dates;
				$this->json = $datees;
				
				echo json_encode($this->json);
				$stm->closeCursor();
				}else{
				   echo $stm; 
				}
				
		}
	}

$class  = new Peticion;


 ?>