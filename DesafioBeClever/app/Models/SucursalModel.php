<?php 
	
	namespace App\Models;

	use CodeIgniter\Model;

	class SucursalModel extends model{
		protected $table = 'sucursales';
		protected $primaryKey = 'Codigo';

		protected $useAutoIncrement = true;

		protected $returnType     = 'array';

		protected $allowedFields = ['Sucursal'];

    // Dates
		protected $useTimestamps = false;
		protected $dateFormat    = 'date';

	}
?>