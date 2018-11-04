<?php 
	// namespace ServiceSigprome;
	require_once('autoload.php');
	// use Conexion as Conexion;
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
		private $preticion = null;
		function __construct(){
			parent::__construct();
			$peticion = $_GET['peticion'];
				// $this->loguearse();

			if($peticion == 'login'){

				$this->loguearse();
			}else if($peticion == 'infopersona'){
				$dni = $_GET['cedulaperson'];
				$proviene = 'infopersona';
				$this->capturardatospaciente($dni,$proviene);
			}else if($peticion == 'Servicios'){
				$cedulaperson = $_GET['cedulaperson'];
				$this->consultarservicios($cedulaperson);
			
			}else if($peticion == 'modifperson'){
				$cedulapersona = $_GET['cedulperson'];
				$correo        = $_GET['correo'];
				$telefono      = $_GET['telefono'];

				$this->modificarperson($cedulapersona,$telefono,$correo);
			}else if($peticion == 'HistorialM'){
				$cedulaperson = $_GET['cedulaperson'];
				$this->capturarhistorial($cedulaperson);
			}else if($peticion == 'consultacita'){
				$cedulapersona = $_GET['cedula'];
				$this->consultarcita($cedulapersona);
			}else if($peticion == 'verificarfecha'){
				$fecha = $_GET['fecha'];
				$this->verificarfecha($fecha);
			}else if($peticion == 'asignarcita'){
				$cedula = $_GET['cedula'];
				$fecha  = $_GET['fecha'];

				settype($cedula, 'integer');

				$this->asignarcita($cedula,$fecha);


			}else if($peticion == 'historialcita'){
				$cedula = $_GET['cedula'];
				settype($cedula, 'integer');

				$this->listarhistorial($cedula);

			} else if($peticion == 'posponercita'){
				$idcita = $_GET['idcita'];
				settype($idcita, 'integer');

				$fecha = $_GET['fecha'];

				$this->posponerfecha($idcita,$fecha);

			}
		}
		public function loguearse(){

					$this->usuario = $_GET['us'];
					$this->contrasena = $_GET['contra'];
					$hast = sha1($this->contrasena);

					settype($this->usuario,"integer");
					$datos = array('cedula'=>$this->usuario,'contrasena'=>$hast);
					parent::beginTransaction();
					$sql = "SELECT * from usuario where cedulapersona =:cedula and contrasena=:contrasena";
					$stm = parent::prepare($sql);
					$result = null;
					try {
						$result = $stm->execute($datos);
					} catch (Exception $e) {
						echo $e->getMessage();
					}
					

					if($result){
							$dates = $stm->fetchALL(PDO::FETCH_ASSOC);
					if(empty($dates)){
							$datees['datos'][]= array('existe' => ,false );
					}else{
						$datees['datos'] = $dates;

						echo json_encode($datees);
					}
					}else{
					
					}
					
					
					//echo json_encode($this->json);
					$stm->closeCursor();		}
	
		public function capturardatospaciente($dni,$proviene){
		 	$usuario = new Usuario($dni,$proviene);
		 }
		 public function consultarservicios($cedulaperson){
	
		 	$servicio = new Servicios();
		 	
		 	$servicio->consultarServicios($cedulaperson);
		 }
		 public function modificarperson($cedulapersona,$telef, $correo){
		 	settype($cedulapersona , 'integer');
		 	settype($telefono , 'integer');
		 	
		 	$cedula = $cedulapersona;
		 	$telefono = $telef;
		 

		 	$usuario = new Persona();
		 	$usuario->modificarperson($cedula,$telefono,$correo);
		 }

		 private function capturarhistorial($cedulaperson){

		 	
		 	$servicios = new Servicios();
		 	$servicios->consultarHistorial( $cedulaperson);
		 }

		public function consultarcita($cedula){
			settype( $cedula, 'integer');
			$cita = new Cita();
			$cita->consultar($cedula);
		}

		private function verificarfecha($fecha){

			$cita = new Cita();
			$cita->verificar($fecha);

		}

		private function asignarcita($cedula,$fecha){
			$cita = new Cita();
			$cita->asignar($cedula,$fecha);


		}

		private function listarhistorial($cedula){
			$cita = new Cita();
			$cita->listar($cedula);
		}


		private function posponerfecha($idcita,$fecha){
			$cita = new Cita();
			$cita->posponer($idcita,$fecha);
		}


		}

$class  = new Peticion;


 ?>