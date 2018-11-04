<?php  
// namespace Conexion;
require_once 'autoload_model.php';

class Conexion extends PDO{

      private $file,$conec;

      private $driver,$port,$host,$dbname,$usuario,$contrasena;
      private $idconexion;
      private $dsn;

      private function cargar_dsn(){

        $this->driver = $this->conec['sigpro']['driver'];
        $this->host = $this->conec['sigpro']['host'];
        (!empty($this->conec['sigpro']['port'])) ? $this->port = $this->conec['sigpro']['port'] : $this->port = '5432';
        $this->dbname = $this->conec['sigpro']['dbname'];
        $caracter = $this->conec['sigpro']['charset'];
        $this->dsn =  $this->driver . ':host='.$this->host.';port='.$this->port.';dbname='.$this->dbname; 
      }
      private function cargar_usuario(){
        $this->usuario = $this->conec['sigpro']['usuario'];
        $this->contrasena = $this->conec['sigpro']['contrasena'];
      }
      /**
       * [Conectar description]
       *  este metodo se utiliza para conectarse a la base de dato... 
       */
      protected function Conectar(){
           try {
         
                parent::__construct($this->dsn,$this->usuario,$this->contrasena);
                parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch (PDOException $e) {
                  die("ERROR DE CONEXION " . $e->getMessage());
            }
      }
          
      /**
       * [__construct primera accion a tomar conectarse y seleccion de bd...]
       */
      public function __construct($file = 'configuracion.ini'){
            $this->file = $file;
            
            if(!$this->conec = parse_ini_file($file, TRUE)) throw new Exception("No se pudo abrir".$file.'.');
        
            $this->cargar_dsn();
            $this->cargar_usuario();
            $this->Conectar();          
      }
}

$conec = new Conexion;
?>
