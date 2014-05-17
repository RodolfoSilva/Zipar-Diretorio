<?php 

	class ZipArchive extends ZipArchive {

		public $folder;
		public $zip_name;
		public $download = true;

	    public function addDir($location, $name) {
	        $this->addEmptyDir($name);
	        $this->addDirDo($location, $name);
	    } 

	    private function addDirDo($location, $name) {
	        $name 		.= '/';
	        $location 	.= '/';

	        $dir = opendir ($location);
	        while ($file = readdir($dir))
	        {
	            if ($file == '.' || $file == '..') continue;
	            $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
	            $this->$do($location . $file, $name . $file);
	        }
	    }
	}

	$zip  = new ZipArchive;

	$zip->folder   = 'folder';
	$zip->zip_name = 'folder.zip';

	$result 	   = $zip->open($zip->zip_name, ZipArchive::CREATE);

	if($result === TRUE) 
	{
	    $zip->addDir($zip->folder, basename($zip->folder));
	    $zip->close();
	}

	if ($zip->download)
	{
	    ob_get_clean();
	    header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Cache-Control: private", false);
	    header("Content-Type: application/zip");
	    header("Content-Disposition: attachment; filename=" . basename($zip->zip_name) . ";" );
	    header("Content-Transfer-Encoding: binary");
	    header("Content-Length: " . filesize($zip->zip_name));
	    readfile($zip->zip_name);

	}
?>