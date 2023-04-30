<?php 
	
	namespace App\Models;

	use CodeIgniter\Model;

	class TipoRegistroModel extends model{
		protected $table = 'tiporegistros';
		protected $primaryKey = 'Codigo';

		protected $useAutoIncrement = true;

		protected $returnType     = 'array';

		protected $allowedFields = ['TipoRegistro'];

    // Dates
		protected $useTimestamps = false;
		protected $dateFormat    = 'date';

	}
?>