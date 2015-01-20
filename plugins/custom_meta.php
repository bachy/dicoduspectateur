<?php 

class Custom_Meta {

	public function before_read_file_meta(&$headers){
		
		$headers['statut'] = 'Statut';
	}

}

?>