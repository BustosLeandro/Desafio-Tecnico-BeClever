<?php 
	
	namespace App\Models;

	use CodeIgniter\Model;

	class EmpleadoModel extends model{
		protected $table = 'empleados';
		protected $primaryKey = 'Codigo';

		protected $useAutoIncrement = true;

		protected $returnType     = 'array';

		protected $allowedFields = ['Nombre', 'Apellido', 'Sexo', 'CodigoSucursal'];

    	// Dates
		protected $useTimestamps = false;
		protected $dateFormat    = 'date';
	}
?>