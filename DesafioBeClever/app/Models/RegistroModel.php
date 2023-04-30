<?php 
	
	namespace App\Models;

	use CodeIgniter\Model;

	class RegistroModel extends model{
		protected $table = 'registros';
		protected $primaryKey = 'Codigo';

		protected $useAutoIncrement = true;

		protected $returnType     = 'array';

		protected $allowedFields = ['CodigoEmpleado','Fecha', 'CodigoTipoRegistro'];

    	// Dates
		protected $useTimestamps = false;
		protected $dateFormat    = 'date';

		public function buscarXFechas($fechaDesde, $fechaHasta){
			//Conexión con la base de datos
			$bd = \Config\Database::connect();
			$tabla = $bd->table('registros r');

        	//Transformo las fechas a string para usarlas en la consulta
			$fechaDesde = $fechaDesde->format('Y-m-d');
			$fechaHasta = $fechaHasta->format('Y-m-d');

        	//Funciones para realizar el inner join
			$tabla->select('e.Sexo, r.Fecha, s.Sucursal');
			$tabla->join('empleados e', 'r.CodigoEmpleado = e.Codigo');
			$tabla->join('sucursales s', 'e.CodigoSucursal = s.Codigo');
			$tabla->where('r.Fecha >=', $fechaDesde); //"BETWEEN"
			$tabla->where('r.Fecha <=', $fechaHasta);
			$consulta = $tabla->get()->getResultArray();

			return $consulta;
		}

		public function buscarConFiltros($filtro,$sucursal,$fechaDesde,$fechaHasta){
			//Conexión con la base de datos
			$bd = \Config\Database::connect();
			$tabla = $bd->table('registros r');

			//Transformo las fechas a string para usarlas en la consulta
			$fechaDesde = $fechaDesde->format('Y-m-d');
			$fechaHasta = $fechaHasta->format('Y-m-d');

			$where = "s.Sucursal = '".$sucursal."' AND (e.Nombre = '".$filtro."' OR e.Apellido ='".$filtro."')";

			//Funciones para realizar el inner join
			$tabla->select('r.Fecha');
			$tabla->join('empleados e', 'r.CodigoEmpleado = e.Codigo');
			$tabla->join('sucursales s', 'e.CodigoSucursal = s.Codigo');
			$tabla->where('r.Fecha >=', $fechaDesde); //"BETWEEN"
			$tabla->where('r.Fecha <=', $fechaHasta);
			$tabla->where($where);
			$consulta = $tabla->get()->getResultArray();

			return $consulta;
		}

	}
?>