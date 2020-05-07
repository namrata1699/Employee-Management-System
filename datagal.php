<?php
	include("config_project.php");
		$id = $_POST['cateid'];
	$s = "select category_name from category_master where categoryid = ".$id;
	$s1 = mysqli_query($c,$s);
	$count = mysqli_num_rows($s1);
	
?>
 <html>
 <head>
	
	 
 </head>
 <body>
 <?php
	while($s2=mysqli_fetch_object($s1))
		{
			$name = $s2 -> category_name;
	?>
 
	<input type="text" value="<?php echo $name?>">
		<?php } ?>
 </body>
 </html>