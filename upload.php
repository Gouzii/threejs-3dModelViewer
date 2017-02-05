<?php
$modeldir = 'obj/uploads/';
$texturedir = 'textures/uploads/';
$uploadmodel = $modeldir . basename($_FILES['file']['name'][0]);
$uploadtexture = $texturedir . basename($_FILES['file']['name'][1]);

echo '<pre>';
$allowedTexTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
$detectedTexType = exif_imagetype($_FILES['file']['tmp_name'][1]);

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$fileMimeType = finfo_file($finfo, $_FILES['file']['tmp_name'][0]);
$info = pathinfo($_FILES['file']['name'][0]);
//Check Texture file type
if (in_array($detectedTexType, $allowedTexTypes)) {
    //Check Model file type
	if ($fileMimeType === "text/plain" && $info["extension"] == "obj") {
        //Move model
		if (move_uploaded_file($_FILES['file']['tmp_name'][0], $uploadmodel)) {
			echo "Le modèle est valide, et a été téléchargé avec succès.\n";
			//Move Texture
			if (move_uploaded_file($_FILES['file']['tmp_name'][1], $uploadtexture)) {
				echo "La texture est valide, et a été téléchargé avec succès.\n\n";
				echo "\n\n Vous allez être redirigé vers la visionneuse dans 5sec\n";
				finfo_close($finfo);
				header( "refresh:5;url=index.php" );
			} else {
				echo "Problème lors du téléchargement de la texture.";
				echo 'Voici quelques informations de débogage :';
				echo "\n\n Vous allez être redirigé vers la visionneuse dans 5sec\n";
				finfo_close($finfo);
                header( "refresh:5;url=index.php" );
			}
		} else {
			echo "Problème lors du téléchargement du modèle.";
			echo "Voici plus d'informations :\n";
			echo 'Voici quelques informations de débogage :';
			echo "\n\n Vous allez être redirigé vers la visionneuse dans 5sec\n";
			finfo_close($finfo);
            header( "refresh:5;url=index.php" );
		}
	} else {
		echo"Votre modèle doit être au format .obj\n";
        print_r($_FILES);
		echo "Name : " . $_FILES['file']['name'][0] . "\n";
		echo "MIME TYPE : " . $fileMimeType . ", Extension : " . $info["extension"];
		echo "\n\n Vous allez être redirigé vers la visionneuse dans 5sec\n";
		finfo_close($finfo);
        header( "refresh:5;url=index.php" );
	}
} else {
	echo "Votre texture doit-être au format .jpg ou .png\n";
    echo $detectedTexType;
    echo "\n\n Vous allez être redirigé vers la visionneuse dans 5sec\n";
	finfo_close($finfo);
    header( "refresh:5;url=index.php" );
}

echo '</pre>';

?>