<!DOCTYPE html>
<!-- 
	Code by Elanis
	Copyright 2018-<?php echo date('Y'); ?> 
	Don't copy this without permission
	I hope this code is readable.
-->
<html>
	<head>
		<title>Nom du site</title>
		<meta charset="UTF-8">
		<!-- On prepare le charset , les mots clés , le fichier css et l'icone du site -->
		<meta name="keywords" content="">
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="shortcut icon" type="image/png" href="./img/favicon.png"/>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="./lib/css/style.css"/>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="./lib/graph/style.css"/>
	</head>
	<body>
		<?php
			if($data['readCache']) { // Read HTML
				readfile($data['cacheName']);
			} else { // Read PHP
				if(file_exists(DIR_VIEW.$data['pageName'])) {
					if($data['writeCache']) {
						ob_start(); // ouverture du tampon
					}

					if(file_exists(DIR_CTRL.$data['pageName'])) {
						include(DIR_CTRL.$data['pageName']);
					}

					include(DIR_VIEW.$data['pageName']);

					if($data['writeCache']) {
						$pageContent = ob_get_contents(); // copie du contenu du tampon dans une chaîne
						ob_end_clean(); // effacement du contenu du tampon et arrêt de son fonctionnement

						file_put_contents($data['cacheName'], $pageContent); // on écrit la chaîne précédemment récupérée ($pageContent) dans un fichier ($data['cacheName'])

						echo $pageContent;
					}
				} else {
					readfile(DIR_ERRORS.'404.html');
				}
			}
		?>
	</body>
</html>