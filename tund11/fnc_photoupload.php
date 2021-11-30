<?php 
	
	$database = "if21_mar_ris";

		
	
	/*function add_watermark($image, $watermark_file){
		$watermark = imagecreatefrompng($watermark_file);
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$watermark_x = imagesx($image) - $watermark_width - 10;
		$watermark_y = imagesy($image) - $watermark_height - 10;
		imagecopy($image, $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height);
		imagedestroy($watermark);
		return $image;
	}*/
	
	/*function save_image($image, $file_type, $target){
		$notice = null;
		
		 if($file_type == "jpg"){
            if(imagejpeg($image, $target, 90)){
                $notice = "Foto salvestamine õnnestus!";
            } else {
                $notice = "Foto salvestamisel tekkis tõrge!";
            }
        }
        
        if($file_type == "png"){
            if(imagepng($image, $target, 6)){
                $notice = "Foto salvestamine õnnestus!";
            } else {
                $notice = "Pildi salvestamisel tekkis tõrge!";
            }
        }
        
        if($file_type == "gif"){
            if(imagegif($image, $target)){
                $notice = "Foto salvestamine õnnestus!";
            } else {
                $notice = "Pildi salvestamisel tekkis tõrge!";
            }
        }
        
        return $notice;
    }
	*/
	
	
	
	function store_photo_data($image_file_name, $alt, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO vprg_photos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issi", $_SESSION["user_id"], $image_file_name, $alt, $privacy);
		if($stmt->execute()){
		  $notice = "Foto lisati andmebaasi!";
		} else {
		  $notice = "Foto lisamisel andmebaasi tekkis tõrge: " .$stmt->error;
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	
	