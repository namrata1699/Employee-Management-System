<?php
	session_start();
	include("config_project.php");
	

?>
<html>
		<head>
				<link rel="stylesheet" type="text/css" href="uses.css">	
				<link rel="stylesheet" type="text/css" href="registeration.css">
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<script  src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.4/sweetalert2.all.js'>
             </script>
				<?php
				
				if($_SERVER['REQUEST_METHOD']=="POST")
			{
				$btnlogout=(isset($_POST['submit_logout']));
				$btnlogin=(isset($_POST['Login']));
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
				 if($btnlogin="Login"){
					 
					 $emailid = ($_POST['empemail']);
					 $catename = ($_POST['cateid']);
					 $password = ($_POST['password']);
					 $s = "select categoryid from category_master where category_name='".$catename."'";
					$s1 = mysqli_query($c,$s);
					$count = mysqli_num_rows($s1);
					while($s2=mysqli_fetch_object($s1))
						{
							$cateid = $s2 -> categoryid;
							
						}
					$query = "select * from employee_master where emailid='".$emailid."' and categoryid=".$cateid." and password='".$password."'";	
						$s1 = mysqli_query($c,$query);
						$count = mysqli_num_rows($s1);
						if($count == 1)
						{
							while($s2=mysqli_fetch_object($s1))
								{
									$_SESSION["logerid"] = $s2 -> emp_id;
									
								//	$aler=$_SESSION["logerid"];
									$_SESSION["cateid"] = $s2 -> categoryid;
									//$val = $s2 -> categoryid;
									$name = $s2 -> emp_name;
									//header("location:p_admin_dashboard.php");
									//$display = "Login Successfull \n Hello ".$s2 -> emp_name;
									date_default_timezone_set('Asia/Kolkata');
										$datetime=date('d-m-y h:i:s');
										$_SESSION["datetime"]=$datetime;
										
									
									echo "<script>
									alert('Login Successfull $name $datetime');
									window.location.href='Dashboard_All.php';
									</script>";
							
									
									
								}
								
						}
						else{
								echo "<script>
								alert('Invalid Login Credential');
								window.location.href='login_page.php';
									</script>";
						}
					
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
	
	
	<div class="body_part">
	
	
		<div class="container">
		
	<?php
	$s = "select category_name from category_master";
	$s1 = mysqli_query($c,$s);
	$count = mysqli_num_rows($s1);
	
	?>
	
	<form name="login" id="login" method="post" >
	
	<h1 id="regisid">Login</h1>
	
	<div class="row">
				<div class="col-25"> 
				EmailID: 
				</div>
				<div class="col-75">
					<input type="text" name="empemail">
			  </div>
		 </div>
		 
		 
		  <div class="row">
				<div class="col-25"> 
					Password: 
				</div>
				<div class="col-75">
				<input type="password" name="password">
			 </div>
		 </div>
	
	<div class="row">
		 <div class="col-25">
			 <label for="Image">Category: </label>
		 </div>
		 <div class="col-75">
	<select name="cateid"  id="cateid">
	<?php
		while($s2=mysqli_fetch_object($s1))
		{
			$id = $s2 -> category_name;
	?>
			<option value="<?php echo $id ?>"><?php echo $id?></option>
			
			
	<?php
		}
	?>	
		</select>
		</div>
	</div>
			 
			
							<br>
			<div class="row">
				<input type="submit" value="Login" name="Login">
			</div>
			
			
			
		
		 
	</form>
	</div>
	
			<div class="rightdata">
			<img src="./img_project/login" width="280px" height="280px">
			</div>
	</div>
	
	
	</body>
</html>