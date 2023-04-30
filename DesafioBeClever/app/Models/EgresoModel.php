<?php 
	
	namespace App\Models;

	use CodeIgniter\Model;

	class EgresoModel extends model{
		protected $table = 'egresos';
		protected $primaryKey = 'CodigoEmpleado';

		protected $useAutoIncrement = true;

		protected $returnType     = 'array';

		protected $allowedFields = ['Fecha'];

    // Dates
		protected $useTimestamps = false;
		protected $dateFormat    = 'date';

	}
}