<?php
	$author_name = "Marleene Ristjan";
	$full_time_now = date("d.m.Y  H:i:s");
	$weekday_now = date("N");
	$hour_now = date("H");
	$day_category = "lihtsalt päev";
	//echo $weekday_now;
	// võrdub == suurem/väiksem ... < > <= >=  pole võrdne (excelis <>) !=
	if($weekday_now <= 5){
		$day_category = "koolipäev";
		if($hour_now < 6 or $hour_now >= 23){
			$part_of_day = "uneaeg";
		}
		if($hour_now >= 6 and $hour_now < 18){
			$part_of_day = "koolipäev";
		}
		if($hour_now >= 18 and $hour_now < 23){
			$part_of_day = "puhkeaeg";
		}
	} else {
		$day_category = "puhkepäev";
		if($hour_now < 9){
			$part_of_day = "uneaeg";
		}
		if($hour_now >= 21){
			$part_of_day = "vaba aeg";
		}
	}
	$weekday_names_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "rede", "laupäev", "pühapäev"]; //list
	//echo $weekday_names_et [2];
	
	//if($hour_now < 7 or $hour_now > 23)
	//if($hour_now > 8 and $hour_now < 18) 
	
	//juhusliku foto lisamine
	$photo_dir = "photos/";
	//loen kataloogi sisu
	$all_files = scandir($photo_dir);
	$all_real_files = array_slice($all_files, 2);
	
	//sõelume välja päris pildid
	$photo_files=[];
	$allowed_foto_types = ["image/jpeg", "image/png"];
	foreach($all_real_files as $file_name){
		$file_info = getimagesize($photo_dir .$file_name);
		if(isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_foto_types)){
				array_push($photo_files, $file_name);
			}//if in array
		}//if isset lõppeb
	}//foreach lõppes
	
	//var_dump($all_real_files);
	//loen massiivi elemendid kokku
	$file_count = count($photo_files);
	//loosin juhusliku arvu (min peab olema 0 ja max count - 1)
	$photo_num = mt_rand(0, $file_count - 1);
	//<img src = "kataloog/fail" alt = "Tallinna Ülikool">
	$photo_html =  '<img src ="' .$photo_dir .$photo_files[$photo_num] .'"alt="Tallinna Ülikool">';
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, veebiprogrammeerimine</title>
</head>
<body>
	<h1><?php echo $author_name; ?>, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="http://www.tlu.ee/dt"> Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<img src="tlu.jpg" alt="Tallinna Ülikooli Terra hoone" width="600">
	<p>Lehe avamise hetk: <?php echo $weekday_names_et[$weekday_now - 1] .", " . $full_time_now .", ". $day_category; ?>.</p>
	
	<?php echo $photo_html; ?>
	
</body>
</html>