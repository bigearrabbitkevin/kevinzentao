<?php

/*
 * This file is part of the overtrue/pinyin.
 *
 * (c) 2016 overtrue <i@overtrue.me>
 */
class kvbomcheck
{
	public $AutoRemoveBom = '1'; //auto remove ,'1' or '0'
	public $BomFileList = array(); //file list
	public $CountChecked = 0; //checked count for one time check
	
	/**
	 * Initial.
	 *
	 * @access public
	 * @return void
	 */
	public function Initial() {
		$this->AutoRemoveBom = 1;
		$this->BomFileList = array();
		$this->CountChecked = 0;
	}
	
	/**
	 * checkdir.
	 *
	 * @access public
	 * @param  string $basedir file directory
	 * @return void
	 */
	public function checkdir($basedir) {
		if ($dh = opendir ( $basedir )) {
			while ( ($file = readdir ( $dh )) !== false ) {
				if ($file == '.' || $file == '..' || $file == '.svn') continue;
				if (! is_dir ( $basedir . "/" . $file )) { // base folder
					$res = $this->checkBOM ( "$basedir/$file" );
				} else {
					$dirname = $basedir . "/" .$file; // filename
					$this->checkdir ( $dirname ); // check
				}
			}
			closedir ( $dh );
		}
	}

	/**
	 * checkBOM for one file.
	 *
	 * @access public	
	 * @param  string $filename file path
	 * @return void
	 */	
	public function checkBOM($filename) {
		$contents = file_get_contents ( $filename );
		$this->CountChecked ++;
		$charset [1] = substr ( $contents, 0, 1 );
		$charset [2] = substr ( $contents, 1, 1 );
		$charset [3] = substr ( $contents, 2, 1 );
		if (ord ( $charset [1] ) == 239 && ord ( $charset [2] ) == 187 && ord ( $charset [3] ) == 191) { // 
			$this->BomFileList[] = $filename ;//save list
			if ($this->AutoRemoveBom == 1) {
				$rest = substr ( $contents, 3 );
				$this->rewrite ( $filename, $rest );
				return 2;
			} else {
				return 1;
			}
		} 
		return 0;
	}

	/**
	 * rewrite bom file.
	 *
	 * @access public
	 * @param  string $filename file path
	 * @param  string $data file contents
	 * @return void
	 */	
	public function rewrite($filename, &$data) {
		$filenum = fopen ( $filename, "w" );
		flock ( $filenum, LOCK_EX );
		fwrite ( $filenum, $data );
		fclose ( $filenum );
	}
}
