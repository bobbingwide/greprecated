<?php // (C) Copyright Bobbing Wide 2017

$files = scandir_recursively( dirname( dirname( __FILE__ ) ) );
print_r( $files );


function scandir_recursively( $dir ) {
	$result = array();
	$files = scandir( $dir );
	//print_r( $files );
	
	foreach ( $files as $file ) {
		if ( $file !== "." && $file != ".." && $file != ".git" ) {
			if ( is_dir( $dir . DIRECTORY_SEPARATOR . $file ) ) {
				$sub_files = scandir_recursively( $dir . DIRECTORY_SEPARATOR . $file  );
				foreach ( $sub_files as $sub_file ) {
					$result[] = $file. DIRECTORY_SEPARATOR .  $sub_file;
				}
			}	else {
				$result[] = $file;
			}
		}
	}
	return $result;
}

/*	

function test_scandir_recursively() {

	$files = $this->scandir_recursively( __DIR__ );
	$this->asse


}

*/
	
