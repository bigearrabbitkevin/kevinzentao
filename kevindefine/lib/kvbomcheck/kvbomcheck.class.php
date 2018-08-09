<?php

/*
 * This file is part of the overtrue/pinyin.
 *
 * (c) 2016 overtrue <i@overtrue.me>
 */
class kvbomcheck
{
	public $auto = 1;


	public function checkdir($basedir) {
		if ($dh = opendir ( $basedir )) {
			$html = "<div style='margin: 20px 0 0 50px;'>";
			$str = "";
			while ( ($file = readdir ( $dh )) !== false ) {
				if ($file != '.' && $file != '..') {
					if (! is_dir ( $basedir . "/" . $file )) { // ������ļ�
						$res = $this->checkBOM ( "$basedir/$file" );
						if(!empty($res))
							$str .= "<p>filename: $basedir/$file " . $res . " <br></p>";
					} else {
						$dirname = $basedir . "/" .$file; // �����Ŀ¼
						$this->checkdir ( $dirname ); // �ݹ�
					}
				}
			}
//			if(empty($str)) $str = "没有检测到有BOM头的文件";
			$html = $html. $str. "</div>";
			echo $html;
			closedir ( $dh );
		}
	}

	public function checkBOM($filename) {
		global $auto;
		$contents = file_get_contents ( $filename );
		$charset [1] = substr ( $contents, 0, 1 );
		$charset [2] = substr ( $contents, 1, 1 );
		$charset [3] = substr ( $contents, 2, 1 );
		if (ord ( $charset [1] ) == 239 && ord ( $charset [2] ) == 187 && ord ( $charset [3] ) == 191) { // BOM ��ǰ�����ַ���ASCII ��ֱ�Ϊ 239 187 191
			if ($auto == 1) {
				$rest = substr ( $contents, 3 );
				$this->rewrite ( $filename, $rest );
				return ("<font color=red>BOM found, automatically removed.</font>");
			} else {
				return ("<font color=red>BOM found.</font>");
			}
		} else
			return "";
//			return ("BOM Not Found.");
	}

	public function rewrite($filename, $data) {
		$filenum = fopen ( $filename, "w" );
		flock ( $filenum, LOCK_EX );
		fwrite ( $filenum, $data );
		fclose ( $filenum );
	}
}
