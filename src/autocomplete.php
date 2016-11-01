<?php 
	// Create recursive dir iterator which skips dot folders
	$dir = new RecursiveDirectoryIterator('/Users/jiamin/Desktop/3219Git/repos/'.$_SESSION['repo_name'], FilesystemIterator::SKIP_DOTS);
	
	// Flatten the recursive iterator, folders come before their files
	$it  = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);
	
	// Maximum depth is 1 level deeper than the base folder
	$it->setMaxDepth(1);
	
	// Basic loop displaying different messages based on file or folder
	foreach ($it as $fileinfo) {
		if ($fileinfo->isFile()) {
			//printf("%s\n", $fileinfo->getFilename());
			$data[] = urlencode($fileinfo->getFilename());
		}
	}
	echo json_encode($data);
?>