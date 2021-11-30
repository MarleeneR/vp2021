<?php

	//alustame sessiooni
	require_once("use_session.php");

	require_once("../../../config.php");
	require_once("fnc_photoupload.php");
	require_once("fnc_general.php");
	require_once("classes/Photoupload.class.php");
	
	
	$news_notice = null;
	$expire = new DateTime("now");
	$expire->add(new DateInterval("P7D"));
	$expire_date = date_format($expire, "Y-m-d");
    
    $photo_file_name_prefix = "vpnews_";
    $photo_file_size_limit = 1.2 * 1024 * 1024;
    $photo_width_limit = 600;
    $photo_height_limit = 400;
	$thumbnail_width = $thumbnail_height = 100;
    $watermark_file = "../pics/vp_logo_color_w100_overlay.png";
	
    
	
	if(isset($_POST["news_submit"])){   
		//kui uudisele on valitud foto, siis see salvestage esimesena ja lisage esimesena ka andmetabelisse (uudisefotodel eraldi andmetabel)
		// siis lisate uudise koos uudise pealkirja, aegumise ja foto id-ga eraldi andmetabelisse
		//andmebaasi salvestamisel saab pärast execute() käsku just salvestanud kirje id kätte:
		//$muutuja=$conn->insert_id;
		//uudise sisu peaks läbima funktsiooni test_input(uudis) (fnc_general.php):
		//seal on htmlspecialchars() funktsioon, mis teisendab html märgised (näiteks: <--> ;
		//tagasi saab htmlspecialchars_decode(uudis andmebaasis)
		   
		//aegumistähtaja saate date inputist
		//uudiste näitamisel võrdlete SQL lauses andmebaasis olevat aegumiskuupäeva tänasega 
		//$today = data("Y-m-d");
		//SQL-is WHERE expire >= ?
		}
    
			
    $to_head = '<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>' ."\n";
	
    require("page_header.php");


?>


	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimub <a href="http://www.tlu.ee/dt"> Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>

	<hr>
	 <ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="home.php">Avaleht</a></li>
    </ul>
	<hr>
	<h2>Uudise lisamine</h2>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	<label for="news_input">Uudise sisu</label>
	<br>
	<textarea id="news_input" name="news_input"></textarea>
	 <script>CKEDITOR.replace( 'news_input' ); </script>
	<br>
	<label for="expire_input">Uudis aegub pärast</label>
	<input type="date" id="expire_input" name="expire_input" value="<?php echo $expire_date; ?>">
	<br>
       <label for="photo_input">Vali pildifail</label>
        <input type="file" name="photo_input" id="photo_input">
        <br>
        
        
        <input type="submit" name="news_submit" id="news_submit" value="Salvesta uudis">
		
    </form>
    <span><?php echo $news_notice; ?></span>
	
</body>
</html>