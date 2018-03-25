<?php 
	$tweettobedelete = $_GET['tweet'];
	delete($tweettobedelete);
	header("Location:index.php?page=yourtweets");
?>
