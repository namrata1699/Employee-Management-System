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
			 
			 
			 <meta name="viewport" content="width=device-width, initial-scale=1">
					<style>
/* Button used to open the contact form - fixed at the bottom of the page */
.open-button {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 28px;
  width: 280px;
}

/* The popup form - hidden by default */
.form-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
}

/* Full-width input fields */
.form-container input[type=text], .form-container input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container input[type=text]:focus, .form-container input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container .btn {
  background-color: #4CAF50;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}
					</style>
			 
			 
			 
				<?php
				
				if($_SERVER['REQUEST_METHOD']=="POST")
			{
				$btnlogout=(isset($_POST['submit_logout']));
				$btnapplication=(isset($_POST['Application']));
				
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
					}else if($btnapplication == "Application"){
						
						
							$lvname = ($_POST['leavename']);
							$lvid=0;
							$getidquery = "select leaveid from leave_master where leavename='".$lvname."'";
							if($get=mysqli_query($c,$getidquery))
							{
								
								while($lidres=mysqli_fetch_object($get))
									{
										$lvid = $lidres -> leaveid;
									}
								
							}
							
							
							
							
							$fdate = ($_POST['fdate']);
							$tdate = ($_POST['tdate']);
							if(isset($_SESSION["logerid"])){
							$empid =  $_SESSION["logerid"];}
							
							if(isset($_SESSION["cateid"])){
							$cateid = $_SESSION["cateid"];}
							$status = "Pending";
							$noflve = ($_POST['number']);
							
							$check = "select remaining_leaves from employee_leave where emp_id=".$empid." and leaveid=".$lvid;
							$chkleave=0;
							
							if($cc=mysqli_query($c,$check))
								{
									while($resultchk=mysqli_fetch_object($cc))
										{
											$chkleave = $resultchk -> remaining_leaves;
										}
								}
								
								if($chkleave > $noflve)
								{
							
							
											$qforapply="insert into leave_status(from_date,to_date,emp_id,categoryid,leaveid,status,no_of_leaves) values('$fdate','$tdate',$empid
											,$cateid,$lvid,'$status',$noflve)";
											if(mysqli_query($c,$qforapply))
											{
												echo "<script>
												alert(' Leave Applied');
												window.location.href='leave_page.php';
													</script>";
										
													
											}
								}
								else
								{
									echo "<script>
												alert(' Sorry!! You Cannot Apply this much leave ');
												window.location.href='leave_page.php';
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
		
		
		<div class="part1">
		
		<br>
		
		<div class="titledata">
		
					<b>Data</b>
			
		</div>
	
		
		<?php
		
			if(isset($_SESSION["cateid"]))
			{
			$categoryid = $_SESSION["cateid"];
			}
			
			if($categoryid == 1)
			{
				
				$ename="";
				$leaven="";
				$categoryn="";
				$s = "select * from employee_leave";
				$s1 = mysqli_query($c,$s);
				$count = mysqli_num_rows($s1);
				while($s2=mysqli_fetch_object($s1))
				{
					
					$employeeid = $s2 -> emp_id;
					$leaveid = $s2 -> leaveid;
					$remainingleave = $s2 -> remaining_leaves;
					
					$query1 = "select emp_name,categoryid from employee_master where emp_id=".$employeeid;
					$query2 = "select leavename from leave_master where leaveid=".$leaveid;
					//$query3 = "select category_name from category_master where categoryid=".$categoryid;
					
					//$sff = mysqli_query($c,$query1);
					//$count = mysqli_num_rows($sff);
					//$ssf = mysqli_query($c,$ss);
					//$sssf = mysqli_query($c,$sss);
					
					if($sff=mysqli_query($c,$query1)){
					while($a=mysqli_fetch_object($sff)){
						
						$ename = $a -> emp_name;
						$cid = $a -> categoryid;
						
						$query3 = "select category_name from category_master where categoryid=".$cid;
								if($sssf=mysqli_query($c,$query3)){
									while($aaa=mysqli_fetch_object($sssf)){
						
											$categoryn = $aaa -> category_name;
									}
					
								}
						
						}
					
					}
					
					if($ssf=mysqli_query($c,$query2)){
					while($aa=mysqli_fetch_object($ssf)){
						
						$leaven = $aa -> leavename;
						}
					
					}
					
					
					//echo "Emoloyee Name:".$ename."Leave =  ".$leaven."Category = ".$categoryn."Remainging Leave=".$remainingleave."<br>";
		?>
		<br>
			<div class="ppart">
			
					<b>Employee Name:</b> <?php echo $ename?><br>
					<b>Category:</b><?php echo $categoryn?><br>
					<b>Leave Type:</b><?php echo $leaven?><br>
					<b>Remaing Leaves:</b><?php echo $remainingleave?><br>
								
			</div>
		<br>
			
		
		
		<?php
				}
				
				
				?>
				
				<a href="requestpage.php">Click here to go to request Portal</a>
				
				<?php
				
				
				}else{
					?>
					<br>
					<div class="row">
					
					<?php
					$eid = $_SESSION["logerid"]; 
					$q = "select leaveid,remaining_leaves from employee_leave where emp_id=".$eid;
					$lid=0;
					$lname="";
					$remlve=0;
					if($srun=mysqli_query($c,$q))
					{
						while($result=mysqli_fetch_object($srun))
						{
							$lid = $result -> leaveid;
							$remlve = $result -> remaining_leaves;
							
							$qq = "select leavename from leave_master where leaveid=".$lid;
							if($lrun=mysqli_query($c,$qq))
							{
								
								while($lresult=mysqli_fetch_object($lrun))
									{
										$lname= $lresult -> leavename;
									}
								
							}
							
							//echo "Leave  Name:".$lname."Remaining Leave:".$remlve;
							
							?>
							
							
							
								<div id="aa" style="border-style:outset;padding:50px;width:20%;background-color:#dafae7;margin-left:10%;">
									<b>Leave Type:</b><?php echo $lname?><br>
									<b>Remaing Leaves:</b><?php echo $remlve?><br>
								</div>
							
							
							<?php
						}
					}
					?>
					
					</div>
					
					
					<button class="open-button" onclick="openForm()">Apply For Leave</button>
					
					
					<div class="form-popup" id="myForm">
						<form  class="form-container" method="POST">
						<h1>Leave Form</h1>
								<?php
										$s = "select leavename from leave_master";
										$s1 = mysqli_query($c,$s);
										
	
								?>
	
						<label for="email"><b>Select Leave Type:</b></label>
						<select name="leavename"  id="leavename">
						
						
							<?php
								while($s2=mysqli_fetch_object($s1))
									{
									$lnamee = $s2 -> leavename;
								?>
									<option value="<?php echo $lnamee ?>"><?php echo $lnamee?></option>
			
			
							<?php
									}
								?>							
						</select>

						<label for="psw"><b>From Date</b></label>
						<input type="text" placeholder="From Date" name="fdate" required>
						
						<label for="psw"><b>To Date</b></label>
						<input type="text" placeholder="To Date" name="tdate" required>
						
						<label for="psw"><b>No of Leaves</b></label>
						<input type="text" placeholder="No. of Leaves" name="number" required>

						<button type="submit" class="btn" name="Application" value="Application">Apply</button>
						<button type="button" class="btn cancel" onclick="closeForm()">Close</button>
						</form>
					</div>
					
					
					
					<script>
							function openForm() {
							document.getElementById("myForm").style.display = "block";
							}

							function closeForm() {
							document.getElementById("myForm").style.display = "none";
							}
					</script>
					
					
					<?php
				} 
			?>
			
			
		</div>
</body>
</html>