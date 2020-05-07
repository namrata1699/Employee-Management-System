<?php
	session_start();
	include("config_project.php");
	

?>
<html>
	<head>
			<link rel="stylesheet" type="text/css" href="uses.css">	
			<link rel="stylesheet" type="text/css" href="dashboard.css">	
				
				 <meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
				<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
				
             </script>
				<?php
				
				if($_SERVER['REQUEST_METHOD']=="POST")
			{
				$btnlogout=(isset($_POST['submit_logout']));
				
				if($btnlogout == "logout")
					{
						
					date_default_timezone_set('Asia/Kolkata');
						$outtime=date('d-m-y h:i:s');
						$_SESSION["outtime"]=$outtime; 
						$intime=$_SESSION["datetime"];
						$currentdate=date('d-m-y');
						$employee_id=$_SESSION['logerid'];
						
						//Putting in array for getting only time
						$splitoutime=explode(" ",$outtime);
						$splitintime=explode(" ",$intime);
						
						//Taking Time from array
						$timein=$splitintime[1];
						$timeout=$splitoutime[1];
						
						//Conversion to seconds
						$starttimestamp = strtotime($timein);
						$endtimestamp = strtotime($timeout);
						
						//For Calculating hour
						$difference=round(abs($endtimestamp-$starttimestamp)/3600,2);
						$query = "insert into employee_working(emp_id,in_time,out_time,total_hour,date) values($employee_id,'$timein','$timeout',$difference,'$currentdate')";
						if(mysqli_query($c,$query))
						{
							session_unset();
							session_destroy();
						
							echo "<script>
									alert('Calculated Answer $difference ,Time in: $timein ,Time out: $timeout, Date: $currentdate');
									window.location.href='login_page.php';
									</script>";
							
						}
						else{
							echo "Error in sql";
						}
						
						
						//header("location:login_page.php");
					}
				
					
			}
				
				?>
			
				
	</head>
	<body class="headerwithdata">
	
	
		<div class="whole">
		
			<div class="wholeunder">
		
		<div class="left">
				<div class="logo"><a href="./p_registration.php"><img src="./img_project/EMS_LOGO-removebg-preview.png" width="150" height="150"></a></div>
				<div class="written">Employee Management System</div>
		</div>
		
		
		<div class="right">
				<?php
					if(isset($_SESSION['logerid']) != null)
					{
				?>
						<form name="logout_form"  method="post">
						
						 <button type="submit" class="button" name="submit_logout" value="logout">Logout</button>
						
						</form>
						
				<?php
					}
				else{
				?>
						<!--<form name="login_form" action="./login_page.php" method="post">-->
						
						<a href="login_page"> <button type="submit" class="button">Login</button></a>
						
						<!--</form>-->
				
				
				<?php
					}
					?>
		</div>
		
			</div>
		</div>
		
		
		&nbsp
		<div class="container">
    
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
   <!--  <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
	   <li data-target="#myCarousel" data-slide-to="3"></li>
    </ol> -->



	
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
	
	<?php
	
		$files = glob("carasoul/*.*");
		$count=0;
			for ($i=0; $i<count($files); $i++)
				{
					$num = $files[$i];
					if($count==0){
	?>
	
	
      <div class="item active">
			
			<?php
				echo '<img src="'.$num.'" alt="random image" style="width:100%">';
			?>
      </div>

	<?php
				$count=1;
				}else{
				
	?>

      <div class="item">
        	<?php
				echo '<img src="'.$num.'" alt="random image" style="width:100%">';
			?>
      </div>
    
     <?php
				}
				}
	 ?>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>

		
		
		
	
		
		&nbsp
<div class="main_body" >
				<div class="row">
					
					<div class="column">
						<a href="leave_page.php"><img src="./imgs/leave.jpg" alt="random" height="200px" width="230px"></a>
					</div>
					<div class="column">
						<a href="profile.php"><img src="./imgs/personal_employee.jpg" alt="random" height="200px" width="230px"></a>
					</div>
					
				</div>

	
				<div class="row">			
					<div class="column">
						<a href="statistics.php"><img src="./imgs/stats.png" alt="random" height="200px" width="230px"></a>
					</div>	
					<?php 
						if(isset($_SESSION["cateid"]) != null)
					{
						
						$cateid = $_SESSION["cateid"];
						if($cateid == 1)
						{
							?>
							
							<div class="column">
						<a href="hhh.php">Register New Employee</a>
					</div>
							
							
							
							<?php
						}
					}
						
					
					?>
				</div>
</div>
	
	</body>
</html>