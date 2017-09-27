<?php // (C) Copyright Bobbing Wide 2014-2017
/** 
 * Tabulate usage of certain keywords in the source files
 

   Syntax: [php] oik-batch.php greprecated.php 
	 
	 Original parameters: 
		infile srchstring ( start stop ) options
   
   Uses flh0grep.tab from current directory
   Writes to flh0grep.txt, also in the current directory
   Uses the function doit: to determine which file types to process.
   
   Secret stuff:
   - This is a complete rewrite, in PHP, of the REXX exec written by Herb Miller while at IBM.
   - The FLH prefix refers to the Technical Infrastructure product, which was affectionately known as 'TINS'
   - The change log dates back to 1998. The REXX version was last updated in 2012
   - The original file was flh0grep.rex
   
*/  
/**
 *
 * Implement lineout() in PHP using code from play
 
 */   
if ( !function_exists( "bw_write" ) ) {   
	function bw_write( $file, $line ) {
		// echo 'in bw_write';
		$handle = fopen( $file, "a" );
		// echo "<!-- $handle $file $line-->";
		if ( $handle === FALSE ) {
			 bw_trace_off();
			 // It would be nice to let them know... 
			 $ret = "fopen failed"; 
		} else {
			$bytes = fwrite( $handle, $line );
			$ret = fclose( $handle );
			$ret .= " $bytes $file $line";
    
		}
		return $ret;
	}
	
} else {
	echo "eh what";
}

	
/**
 * Searches current directory for strings
 */
function flh0grep() {  
  $dir = getcwd() . '\\';
  $currentdir = $dir;
  $rc = readgrep();
	initcounts();
	reportcounts();
  //$files = SysFileTree( "*.*", $dir );
	//oik_require( "oik-list-previous-files.php", "oik-batch" );
	//$files = oikb_list_files_in_directory( $dir );
	
	$files = scandir_recursively( $dir );
	
  //print_r( $files );
	processfiles( $files );
	reportcounts();
}

/**
 * Scans directory recursively for files
 *
 * Based on WC_admin_status::scan_template_files
 *
 * @param string $dir
 * @return array relative file names under $dir
 */
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
	
/**
 * Process each file
 */  
function processfiles( $files ) {   
      
   foreach ( $files as $file ) {
      echo $file;
      $doit = doit( $file );
      if ( $doit == 1 ) { 
          report();
      } 
   } // end
   // rc = writeCOUNT();
   //if options('DISCOVER') > 0 then
   //   rc = writestems()
   // return; // TFOUND
}

/*

options: procedure expose options
return wordpos( arg(1), options )

*/  
 
/**
 * initialise 
 */ 
function init() {

//   ver = 0
//   call RxFuncAdd 'SysLoadFuncs', 'RexxUtil', 'SysLoadFuncs'
//   call SysLoadFuncs
//   doptions = 'DETAIL'
//   fnlen = 57
//   stems.0 = 0
//   stems. = 0
//   stemwords = ''
  oik_require( "sft.php", "play" );
}

/**
 * Actually grep this file
 * 
 * @param $file - full file name of file to search
 *
 */   
function forreal( $filename ) {
	isay( "Processing $filename " );
	if ( file_exists( $filename ) ) {
		$lines = file( $filename );
	} else {
		isay( "File $filename does not exist" );
		gob();
	}
  $summ = array();
  $blanks = 0; 
  $lineno = 0;
  foreach ( $lines as $line ) {
		$lineno++;
    $line = trim( $line );
    if ( $line != "" ) {
      summarize( $line, $lineno, $filename );
    } else {
      $blanks++;
    }
  }
}  

/**
 * Populates the grep array with the strings we're searching for
 * 
 * @param string $searchstring
 * @return array strings to seach for 
 */
function readgrep( $searchstring=null ) {
	global $grep; 
  $grep = array();
  if ( $searchstring ) {
    $grep[] = array( 1, $searchstring );  
  }
	if ( file_exists( "flh0grep.tab" ) ) {
		$grep = file( "flh0grep.tab", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
	} else { 
		isay( "Missing flh0grep.tab" );
		gob();
	}
  return $grep;
}

/**
 * Initialise $counts, $grepgroup and $groups array
 * 
 * We're looking for before and after so we count the number we find in each group
 * 
 * flh0grep.tab
 * a 1
 * b 2
 */
function initcounts() {
	global $grep, $counts, $grepgroup, $groups;
	$counts = array();
	$groups = array();
	foreach ( $grep as $grepword ) {
		$grepword = trim( $grepword );
		if ( false !== strpos( $grepword, " " ) ) {
			list( $word, $group ) = explode( " ", $grepword ); 
		} else {
			$word = $grepword;
			$group = "";
		}
		$counts[ $word ] = 0;
		$groups[ $group ] = 0;
		$grepgroup[ $word ] = $group;
	}
}

function reportcounts() {
	global $counts, $groups;
	//print_r( $counts );
		isay( "Function,Count" );
	foreach ( $counts as $grepword => $count ) {
		$line = "$grepword,$count";
		isay( $line );
	}
	foreach ( $groups as $group => $count ) {
		$line = "$group,$count";
		isay( $line );
	}
}





/**
 * Implement REXX say 
 *
 * Originally with option to display as HTML output if variable php was 1
 *
 */           
function say( $arg ) {
  echo $arg . PHP_EOL;
}  

/**
 * write the output to the flh0grep.txt file
 * and the csv file if required
 
 */
function isay( $arg ) {
  say( $arg );
  bw_write( "flh0grep.txt", $arg . PHP_EOL );
  //writecsv( $arg );
}

 
/*
*/
function doit( $fn ) {
  //$pathinfo = pathinfo( $fn );
  $f = pathinfo( $fn, PATHINFO_FILENAME );
  $ext = pathinfo( $fn, PATHINFO_EXTENSION );
	isay( "Considering $f $ext" );
  
  
  $ignore = ignore_file( $f );
  if ( !$ignore ) {
    $ignore = ignore_ext( $ext );
    if ( !$ignore ) {
      $ignore = ignore_other_reason( $fn);
		}
  }
  if ( !$ignore ) {
    forreal( $fn );
  } 
}

function ignore_file( $f ) {
  $f = strtolower( $f );
  $fns = bw_as_array( "flh0grep" ); 
  $ignore = bw_array_get( $fns, $f, false ); 
  return( $ignore );
}

/**
 * Tests if the extension should be ignored
 *
 * @param string $ext
 * @return bool true if the extension should be ignored
 */
function ignore_ext( $ext ) {
  $ext = strtolower( $ext );
  $exts = get_process_exts(); 
  //$ignore = bw_array_get( $exts, $ext, false ); 
	$process = bw_array_get( $exts, $ext, false );
	$ignore = !$process;
  return $ignore;
} 

function ignore_other_reason( $fn ) { 
  return( false );
} 

/**
 * We used to grep for all C related source files:
 * C bnd bmp cfg cls cpp 
 * 
 * Now we're more interested in
 * 
 * 
 * 
 */
function get_process_exts() {
	static $exts = null;
	if ( !$exts ) {
		$exts = array( "php" => true, "inc" => true );
	}
	return $exts;  
}


/** 
 * Function: summarize

   Notes: 
   - matching is case insensitive
   - we prepend a blank since we search for " $grepword(" 
 */
function summarize( $line, $lineno, $file ) {

	global $grepgroup, $counts, $groups;
	$line = " " . strtolower( $line );
	foreach ( $grepgroup as $grepword => $group  ) {
		$needle = " " . strtolower( $grepword ) . "(" ;
		$pos = strpos( $line, $needle );
		if ( $pos !== false ) {
			isay( "$file($lineno) Found $grepword in $line " );
			$counts[ $grepword ]++;
			$groups[ $group ]++;
			break;
		} 
	}
}
 

  


  //$infile = bw_array_get( $argv, 1, "*.*" );   
	//$infile = oik_batch_query_value_from_argv( 1, "*.*" );

  //$searchstring = bw_array_get( $argv, 2, null );
	// = oik_batch_query_value_from_argv( 2, null );
  //$options = bw_array_get( $argv, 3, null );
	oik_require( "libs/bobbfunc.php" );
  
  flh0grep();
	
