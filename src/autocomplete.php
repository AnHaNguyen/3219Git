<?php 
	$root = dirname(__DIR__).'/repos/'.$_SESSION['repo_name'].'/';
	
	$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
	
	$files = array(); 
	
	foreach ($rii as $file) {
	
		if ($file->isDir()){ 
			continue;
		}
	
		$files[] = $file->getPathname(); 
	}
	
	echo json_encode($files);
?>