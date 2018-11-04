<?php 
	/**
	* @autor jhonatan crespo
	*/
	class Servicios 
	{
		private  $conexion;

		
		function __construct(){
			$this->conexion = new Conexion();
		}

		public function consultarServicios($cedula){
			settype($cedula, "integer");
			// echo gettype($cedula);
			$datos = array("cedulapersona"=>$cedula);
				$this->conexion->beginTransaction();
				$sql = "select s.fecha, s.procesado, ts.nombre as tiposervicio, oa.opcion as opcion, etd.estado as estado, etd.condicion
							from servicio as s join tiposervicio as ts on s.id_tiposervicio = ts.id
							join opcionservicio as os on s.id = os.idservicio
							join opcionadministrativa as oa on os.idopcion = oa.id
							join estado as etd on os.idestado = etd.id
							join usuario as pac on s.id_usuario = pac.id
							where pac.cedulapersona = :cedulapersona and ts.nombre = 'ADMINISTRATIVO' and etd.condicion < 2
							ORDER BY s.fecha";

			$stm = $this->conexion->prepare($sql);
			$result = $stm->execute($datos);
			if($result == 1){
				$dates = $stm->fetchAll(PDO::FETCH_ASSOC);
				$datees['datos']= $dates;
				echo json_encode($datees);
				$stm->closeCursor();
			}else{
					$stm->closeCursor();
				echo "no hubo nada";
			}
		}
		public function consultarHistorial($cedulperson){
			settype($cedulperson, "integer");
			$this->conexion->beginTransaction();
			$datos = array('cedulapersona' => $cedulperson);
			$query = "SELECT MAX(ser.fecha) as ultima_visita, tri.talla as altura, tri.peso, tri.presion as presion, 
					tri.glicemia, con.sintoma, con.diagnostico				
					FROM persona as pe JOIN usuario as pac ON pe.cedula = pac.cedulapersona
					JOIN servicio as ser ON ser.id_usuario = pac.id
					JOIN triaje as tri ON tri.id_servicio  = ser.id
					JOIN consulta as con ON con.id_triaje  = tri.id
					WHERE pe.cedula  = :cedulapersona
					GROUP BY tri.talla, tri.peso,tri.glicemia, tri.presion,
									con.sintoma, con.diagnostico, ser.fecha
					ORDER BY ser.fecha desc LIMIT 1";
			$stm = $this->conexion->prepare($query);
			$result = $stm->execute($datos);
			if($result){
				$datos = $stm->fetchAll(PDO::FETCH_ASSOC);
				$dates['datos'] = $datos;
				echo json_encode($dates);
				$stm->closeCursor();
			}

			


		}
	}

 ?>