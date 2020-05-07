<?php
	session_start();
	include("config_project.php");
	

?>
<html>
		<head>
		
		
<style>
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

tr:hover {background-color:#f5f5f5;}

th {
  background-color: #654321;
  color: white;
}
</style>
		
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
		
					<b>Data</b>
			
		</div>
		
		<br>
		<table>
		<tr>
		<th>Employee Name</th>
		<th>In Time</th>
		<th>Out Time</th>
		<th>Working Hour</th>
		<th>Date</th>
		</tr>
		
		<?php
		
		if(isset($_SESSION["cateid"]))
		{
			
			$empname="";
			$intime="";
			$outtime="";
			$workinghour=0.0;
			$date="";
			$empid=0;
			
			$categoryid = $_SESSION["cateid"];
			if($categoryid == 1)
			{
				$query = "select * from employee_working";
					if($aa=mysqli_query($c,$query))
							{
								while($ansa=mysqli_fetch_object($aa))
												{
												 $empid = $ansa -> emp_id;
												 $intime = $ansa -> in_time;
												 $outtime = $ansa -> out_time;
												 $workinghour = $ansa -> total_hour;
												 $date = $ansa -> date ;
												 $underquery = "select emp_name from employee_master where emp_id=".$empid;
												 if($res=mysqli_query($c,$underquery)){
													 while($ans=mysqli_fetch_object($res))
													 {
														 $empname = $ans -> emp_name;
													 }
												 }
												 
												 
												 ?>
												 
												 <tr>
												 <td><?php echo $empname?></td>
												 <td><?php echo $intime?></td>
												 <td><?php echo $outtime?></td>
												 <td><?php echo $workinghour?></td>
												 <td><?php echo $date?></td>
												 </tr>
												 
												 
												 <?php
												 

												}
							}
			}
			else{
				
				if(isset($_SESSION["logerid"]))
				{
					$empname="";
					$intime="";
					$outtime="";
					$workinghour=0.0;
					$date="";
					$empid = $_SESSION["logerid"];
					
					
					$qu = "select * from employee_working where emp_id=".$empid;
					if($aa=mysqli_query($c,$qu))
							{
								while($ansa=mysqli_fetch_object($aa))
									{
										 $intime = $ansa -> in_time;
										$outtime = $ansa -> out_time;
										 $workinghour = $ansa -> total_hour;
										 $date = $ansa -> date ;
										 $underquery = "select emp_name from employee_master where emp_id=".$empid;
												 if($res=mysqli_query($c,$underquery)){
													 while($ans=mysqli_fetch_object($res))
													 {
														 $empname = $ans -> emp_name;
													 }
												 }
												 
												 ?>
												 
												  <tr>
												 <td><?php echo $empname?></td>
												 <td><?php echo $intime?></td>
												 <td><?php echo $outtime?></td>
												 <td><?php echo $workinghour?></td>
												 <td><?php echo $date?></td>
												 </tr>
												 
												 
												 <?php
												 
									}
									
							}
					
				}
				
				
			}
		}
		
		?>
		
		
		</table>

</body>
</html>
		