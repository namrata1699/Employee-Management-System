<?php
	session_start();
	include("config_project.php");
	

?>
<html>
		<head>
		

				<link rel="stylesheet" type="text/css" href="uses.css">	
				<link rel="stylesheet" type="text/css" href="registeration.css">
				<link rel="stylesheet" type="text/css" href="dashboard.css">
				
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<script  src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.4/sweetalert2.all.js'>
				
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

		<br>
		<div class="titledata">
		
					<b>Profile</b>
			
		</div>
		

		<div class="body_part">
		
		
	
		<?php
		
				if(isset($_SESSION["logerid"]))
		{
			$empid=$_SESSION["logerid"];
			
			$empname="";
			
			$cateid=0;
			$category="";
			$address="";
			$contact=0;
			$password="";
			$emailid="";
			$image="";
			
			$query = "Select * from employee_master where emp_id=".$empid;
			
			if($aa=mysqli_query($c,$query))
				{
					while($ansa=mysqli_fetch_object($aa))
						{
								$cateid = $ansa -> categoryid;
								$empname = $ansa -> emp_name;
								$address = $ansa -> address;
								$contact = $ansa -> contact;
								$password = $ansa -> password;
								$emailid = $ansa -> emailid;
								$image = $ansa -> emp_img;
								$query2 = "select category_name from category_master where categoryid=".$cateid;
								
								if($aaa=mysqli_query($c,$query2))
									{
										while($ansaa=mysqli_fetch_object($aaa))
											{
												$category = $ansaa -> category_name;
											}
									}
									
									
									?>
									<div class="l">
									
									<b>Employee Name : </b> <?php echo $empname?><br><br>
									<b>Category : </b> <?php echo $category?><br><br>
									<b>Email id : </b> <?php echo $emailid?><br><br>
									<b>Contact : </b> <?php echo $contact?><br><br>
									<b>Address : </b> <?php echo $address?><br><br>
									<b>Image : </b> <img src="img_project/<?php echo $image?>" width="100px" height="100px"><br><br>
									<a href="statistics.php">Click Here To View Your Statistics</a>
									
									
									</div>
									
									
									<?php
									
						}
				}
		}
			
		?>
		
			
		</div>

</body>
</html>
		