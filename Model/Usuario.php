<?php 

	// namespace modelo;
	require_once 'Persona.php';
	// require_once 'autoload_model.php';
	// use modelo\Persona;
	// use modelo\Conexion;
	/**
	* @Description: Esta clase se utiliza para servir como mediador entre las peticiones de nuestra app SIGPROME, 
	* con la base de datos mysql en un servidor remoto...  
	* @autor Crespo jhonatan 
	*/
	class Usuario extends Persona{
		// CAMPOS DE CLASES 
		private $conexion;
		private $idusuario = 0;
		private $usuario,$contrasena,$foto;
		private $json = array();


		function __construct($cedula,$proviene){
			

			if($proviene == 'infopersona'){
				$this->consultarpersona($cedula);
			}else if(isset($_GET['id'])){
				$this->usuario = $_GET['us'];
				$this->contrasena = $_GET['contra'];
				settype($this->usuario,"integer");
				$this->consultarusuario();
			}
		}
		public function consultarusuario(){

				$datos = array('cedula'=>$this->usuario,'contrasena'=>$this->contrasena);
				$conexion = new Conexion();
				$conexion->beginTransaction();
				

				$sql = "SELECT * from usuario where cedulapersona = :cedula and contrasena = :contrasena";
				$stm = $this->conexion->prepare($sql);
				$stm->execute($datos);
				$dates = $stm->fetchAll(PDO::FETCH_BOTH);
				$this->json = $dates['datos'] = $datos;
				echo json_decode($this->json);
				$stm->closeCursor();
		}
		public function consultarpersona($cedulaperson){
			settype($cedulaperson, "integer");
			$this->datos = array('cedulaperson'=>$cedulaperson);
			$conexion = new Conexion();
			$conexion->beginTransaction();

			 $primera = "select p.cedula,p.nombre,p.apellido,p.genero,p.fechanacimiento as fechanac,UPPER(u.email) as email,p.telefono, tp.nombre as tipopersona,
					UPPER(CONCAT(p.direccion,' de la parroquia ',parr.nombre,' del municipio ',mun.nombre,' del Estado ',  est.nombre)) as direccion
					from persona as p
					join parroquia as parr on p.id_parroquia = parr.id
					join municipio as mun on parr.id_municipio = mun.id
					join edo as est on mun.id_estado = est.id 
					join tipopersona as tp on p.id_tipopersona = tp.id
					join usuario as u on p.cedula = u.cedulapersona
					where p.cedula = :cedulaperson";
			 $stm = $conexion->prepare($primera);
			 $result  = $stm->execute($this->datos);
			
			 if($result == 1 ){
			 	$dates = $stm->fetch(PDO::FETCH_ASSOC);
			 	$datees['datos'][] = $dates;
			 	echo json_encode($datees);
			 	$stm->closeCursor();
			 }else{
			 	$stm->closeCursor();
			 }
		}
		public function getStament(){
			return $this->stamen;
		}

		private $stamen;
		public $datos;
	}

 ?>