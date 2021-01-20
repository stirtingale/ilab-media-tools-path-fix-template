<?php
/*
Template Name: Find Missing Images - Update URLS
*/
header("Content-Type: text/plain");

// https://stackoverflow.com/questions/7991425/php-how-to-check-if-image-file-exists
function fileExists($filePath) {
      return is_file($filePath) && file_exists($filePath);
}
// https://stackoverflow.com/questions/25067241/how-to-list-all-files-in-folders-and-sub-folders-using-scandir-and-display-them
function scanDirAndSubdir($dir, &$out = []) {
    $sun = scandir($dir);

    foreach ($sun as $a => $filename) {
        $way = realpath($dir . DIRECTORY_SEPARATOR . $filename);
        if (!is_dir($way)) {
            $out[] = $way;
        } else if ($filename != "." && $filename != "..") {
            scanDirAndSubdir($way, $out);
            $out[] = $way;
        }
    }

    return $out;
}
// 
// 
// QUERY EVERYTHING IN THE MEDIA LIBRARY
// 
// 
$media_query = new WP_Query(
    array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 5000,
    )
);
// WHATS OUR WP UPLOAD DIR?
$wpupload = wp_upload_dir();
$wploadpath = $wpupload['basedir'];
// BUILD AN ARRAY OF EVERYTHING!
$files = scanDirAndSubdir($wploadpath);
foreach ($media_query->posts as $post) {
	// DOES IT EXIST
	$attachment_id = $post->ID;
    if(!fileExists(get_attached_file($attachment_id))){

    	echo "Missing = ";
		$missingitem = get_attached_file($attachment_id);
		$missing = basename( $missingitem ); 
		$oldpath = pathinfo($missingitem)['dirname'];
		$oldpath = explode("/", $oldpath);
		$oldpath = $oldpath[count($oldpath) - 2] . '/' . $oldpath[count($oldpath) - 1];
		echo $missing;
	    echo "\n";
		echo $missingitem;
	    echo "\n";
		$data = wp_get_attachment_metadata($attachment_id);
		foreach ($files as $file) {
			// GRAB THE FILENAME
			$filename = basename($file);
			// DOES THIS MATCH THE FILENAME OF OUR MISSING FILE?
		    if ($missing == $filename) {
		    	echo $file;
		    	// LETS GET THE MISSING FOLDER
		    	$newpath = pathinfo($file)['dirname'];
				$newpath = explode("/", $newpath);
				$newpath = $newpath[count($newpath) - 2] . '/' . $newpath[count($newpath) - 1];
			    echo "\n";
		    	echo "Found ↑";
		    	// LETS UPDATE THE ITEM
		    	// REPLACE BASE FILE & SIZES WITH CORRECT DATE
	        	$meta = wp_get_attachment_metadata($attachment_id);
		       	$meta['file'] = str_replace( $oldpath, $newpath, $meta['file'] );
		        foreach ( (array)$meta['sizes'] AS $size => $meta_size ) {
		            $meta['sizes'][$size]['file'] = str_replace( $oldpath, $newpath, $meta['sizes'][$size]['file'] );
		        }
			    echo "\n";
		        echo "OLD => ". $oldpath;
			    echo "\n";
		        echo "NEW => ". $newpath;
			    echo "\n";
			    // PUSH THE DIR CHANGES BACK TO WP
		        update_attached_file($attachment_id, $file );
		        wp_update_attachment_metadata( $attachment_id, $meta );
		    }
		}
	    echo "\n";
    }
}