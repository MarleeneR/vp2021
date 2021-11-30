<?php 
	
	$database = "if21_mar_ris";

	function show_latest_public_photo(){
		$photo_html = null;
		$privacy = 3;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
			$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, alttext FROM vprg_photos WHERE id = (SELECT MAX(id) FROM vprg_photos WHERE privacy = ? AND deleted IS NULL)");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($id_from_db, $alttext_from_db);
		$stmt->execute(); 
		if($stmt->fetch()){
			//<img scr="kataloog/fail" alt="tekst">
			//<img src="show_public_photo.php?
			$photo_html = '<img src="show_public_photo.php?photo=' .$id_from_db .'" alt="';  //photo_normal_upload_dir
			if(empty($alttext_from_db)){
				$photo_html .= "Üleslaetud foto";
			}else{
				$photo_html .= $alttext_from_db;
			}
			$photo_html .= '">' ."\n";
		}else{
			$photo_html = "<p>Kahjuks pole ühtegi avalikku fotot üles laetud</p>";
		}
		$stmt->close();
		$conn->close();
		return $photo_html;
	}
	
	//
	function read_public_photo_thumbs($privacy, $page, $limit){
		$skip = ($page - 1) * $limit;
		$photo_html = null;
		$avarage_rating_from_db = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//$stmt = $conn->prepare("SELECT filename, alttext FROM vprg_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC");
		//$stmt = $conn->prepare("SELECT filename, alttext FROM vprg_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT 5");
		//$stmt = $conn->prepare("SELECT id, filename, created, alttext FROM vprg_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT ?, ?");
		//echo $conn->error;
		//$stmt->bind_param("iii", $privacy, $skip, $limit);
		//$stmt->bind_result($id_from_db, $filename_from_db, $created_from_db, $alttext_from_db);//v
		
		$stmt =$conn->prepare("SELECT vprg_photos.id, filename, vprg_photos.created, alttext, firstname, lastname, AVG(rating) as AvgValue FROM vprg_photos JOIN vprg_users ON vprg_photos.userid = vprg_users.id LEFT JOIN vprg_photoratings ON vprg_photoratings.photoid = vprg_photos.id WHERE vprg_photos.privacy >= ? AND deleted IS NULL GROUP BY vprg_photos.id DESC LIMIT ?,?");

		echo $conn->error;
		
		$stmt->bind_param("iii", $privacy, $skip, $limit);
		$stmt->bind_result($id_from_db, $filename_from_db, $created_from_db, $alttext_from_db, $firstname_from_db, $lastname_from_db, $avarage_rating_from_db);
		$stmt->execute(); 
		while($stmt->fetch()){
			//<div>
			//<img scr="kataloog/fail" alt="tekst" class="thumbs" data-id="x" data-fn="failinimi.jpg">
			//...
			//</div>
			$photo_html .= '<div class="gallerythumb">' ."\n";
			$photo_html .= '<img src="' .$GLOBALS["photo_upload_thumb_dir"] .$filename_from_db .'" alt="';    //photo_upload_thumb_dir
			if(empty($alttext_from_db)){
				$photo_html .= "Üleslaetud foto";
			}else{
				$photo_html .= $alttext_from_db;
			}
			$photo_html .= '" class="thumbs" data-id="' .$id_from_db .'" data-fn="' .$filename_from_db .'">' ."\n";
			$photo_html .= "<p>Lisatud: " .date_to_est_format($created_from_db) ."</p> \n";
			$photo_html .= "<p>Lisas: " .$firstname_from_db ." " .$lastname_from_db ."</p> \n";
			if(!empty($avarage_rating_from_db)){
				$photo_html .= '<p id="rating" .id_from_db>' ."Keskmine hinne: "  .round($avarage_rating_from_db, 2) ."</p> \n";
			}else{
				$photo_html .= "<p> Pole hinnatud " ."</p> \n";
			}
			$photo_html .= "</div> \n"; 
		}
		if(empty($photo_html)){
			$photo_html = "<p>Kahjuks pole ühtegi avalikku fotot üles laetud</p>";
		}
		$stmt->close();
		$conn->close();
		return $photo_html;
	}
	
	function count_public_photos($privacy){
		$photo_count = 0;
	$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
	$conn->set_charset("utf8");
	$stmt = $conn->prepare("SELECT COUNT(id) FROM vprg_photos WHERE privacy >= ? AND deleted IS NULL");
	echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($count_from_db);
		$stmt->execute(); 
		if($stmt->fetch()){
			$photo_count = $count_from_db;
		}
		$stmt->close();
		$conn->close();
		return $photo_count;
	}
	
	function count_own_photos(){
		$photo_count = 0;
	$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
	$conn->set_charset("utf8");
	$stmt = $conn->prepare("SELECT COUNT(id) FROM vprg_photos WHERE userid = ? AND deleted IS NULL");
	echo $conn->error;
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->bind_result($count_from_db);
		$stmt->execute(); 
		if($stmt->fetch()){
			$photo_count = $count_from_db;
		}
		$stmt->close();
		$conn->close();
		return $photo_count;
	}
	
	function read_own_photo_thumbs($page, $limit){
		$skip = ($page - 1) * $limit;
		$photo_html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, filename, created, alttext FROM vprg_photos WHERE userid = ? AND deleted IS NULL ORDER BY id DESC LIMIT ?, ?");
		echo $conn->error;
		$stmt->bind_param("iii", $_SESSION["user_id"], $skip, $limit);
		$stmt->bind_result($id_from_db, $filename_from_db, $created_from_db, $alttext_from_db);
		$stmt->execute(); 
		while($stmt->fetch()){
			//<div>
			//<a href="edit_photo.php?photo=x">
			//<img src="kataloog/fail" alt="tekst">
			//...
			//</div>
			$photo_html .= '<div class="gallerythumb">' ."\n";
			$photo_html .= '<a href="edit_gallery_photo.php?photo=' .$id_from_db .'">';
			$photo_html .= '<img src="' .$GLOBALS["photo_upload_thumb_dir"] .$filename_from_db .'" alt="';
			if(empty($alttext_from_db)){
				$photo_html .= "Üleslaetud foto";
			}else{
				$photo_html .= $alttext_from_db;
			}
			$photo_html .= '" class="thumbs"></a>' ."\n";
			$photo_html .= "<p>Lisatud: " .date_to_est_format($created_from_db) ."</p> \n";
			$photo_html .= "</div> \n";
		}
		if(empty($photo_html)){
			$photo_html = "<p>Kahjuks pole ühtegi avalikku fotot üles laetud</p>";
		}
		$stmt->close();
		$conn->close();
		return $photo_html;
	}
	
	
	
	
	function read_own_photo($photo){
		$photo_data = [];
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
	$stmt = $conn->prepare("SELECT filename, alttext, privacy FROM vprg_photos WHERE id = ? AND userid = ? AND deleted IS NULL");
		echo $conn->error;
	$stmt->bind_param("ii", $photo, $_SESSION["user_id"]);
	$stmt->bind_result($filename_from_db, $alttext_from_db, $privacy_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			array_push($photo_data, true);
			array_push($photo_data, $filename_from_db);
			array_push($photo_data, $alttext_from_db);
			array_push($photo_data, $privacy_from_db);
		}else{		
			array_push($photo_data, false);
		}
		$stmt->close();
		$conn->close();
		return $photo_data;
	}
	
	function photo_data_update($photo, $alttext, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
	$stmt = $conn->prepare("UPDATE vprg_photos SET alttext = ?, privacy = ? WHERE id = ? AND userid = ?");
		echo $conn->error;
	$stmt->bind_param("siii", $alttext, $privacy, $photo, $_SESSION["user_id"]);
	if($stmt->execute()){
				$notice = "Andmed on muudetud";
		}else{
				$notice = "Andmete muutmisel tekkis viga";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function delete_photo($photo){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
	$stmt = $conn->prepare("UPDATE vprg_photos SET deleted = NOW() WHERE id = ? AND userid = ?");
		echo $conn->error;
	$stmt->bind_param("ii", $photo, $_SESSION["user_id"]);
	if($stmt->execute()){
		$notice = "ok";
	}else{
		$notice = "Foto kustutamine ebaõnnestus";
	}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
		
		