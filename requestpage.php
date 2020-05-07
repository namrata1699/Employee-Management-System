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

tr:nth-child(even){background-color: #f2f2f2}

tr:hover {background-color:#A9A9A9;}

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
				
				
				if($_SERVER['REQUEST_METHOD']=="GET")
					{
						$email="";
						$id=(isset($_GET['id'])?$_GET['id'] : '');
						$empid=(isset($_GET['empid'])?$_GET['empid'] : '');
						$action=(isset($_GET['action'])?$_GET['action'] : '');
						$empname=(isset($_GET['empname'])?$_GET['empname'] : '');
						$leavname=(isset($_GET['lname'])?$_GET['lname'] : '');
						$fromdate=(isset($_GET['fdate'])?$_GET['fdate'] : '');
						$todate=(isset($_GET['tdate'])?$_GET['tdate'] : '');
						
						if($action == "delete"){
						$sql="Delete from leave_status where statid =$id";
						if(mysqli_query($c,$sql)){
							
							$emailquery = "select emailid from employee_master where emp_id=".$empid;
							if($eans=mysqli_query($c,$emailquery))
										{
											while($eresult=mysqli_fetch_object($eans))
												{
													$email = $eresult -> emailid; 
												}
										}
							
								$subject = "Leave Denied";
								$bdy = "Hello ".$empname." Your ".$leavname." From ".$fromdate." Todate ".$todate." is Rejected";
						require 'phpmailer/PHPMailerAutoload.php';
						require 'phpmailer/class.phpmailer.php';
						require 'phpmailer/class.smtp.php';
						$mail = new PHPMailer;
						$mail -> IsSmtp();
						$mail -> SMTPDebug = 0;
						$mail -> SMTPAuth = true;
						$mail -> SMTPSecure = 'ssl';
						$mail -> Host = "smtp.gmail.com";
						$mail -> Port = 465 ;//Or 587
						$mail -> IsHTML(true);
						$mail -> Username = "yourmail@gmail.com";
						$mail -> Password = "yourpassword";
						$mail -> SetFrom("namrata.t.imca16@ahduni.edu.in");
						$mail -> Subject = $subject;
						$mail -> Body = $bdy;
						$mail -> AddAddress($email);
						
						if(!$mail -> Send()){
							echo "<script>
									alert('Mail Not Sent');
									</script>";
									echo "mail erro ".$mail->ErrorInfo;
						}else{
							echo "<script>
									alert('Mail  Sent');
									</script>";
						}
						
					}
				}
						else if($action == "accept"){
						$no=(isset($_GET['nleaves'])?$_GET['nleaves'] : '');
						$lvid=(isset($_GET['lvid'])?$_GET['lvid'] : '');
						$realve=0;
						
							$qw = "select remaining_leaves from employee_leave where emp_id=".$empid." and leaveid=".$lvid;
							if($aa=mysqli_query($c,$qw))
							{
								while($ansa=mysqli_fetch_object($aa))
												{
												 $realve = $ansa -> remaining_leaves;	
												}
							}
						
							$final = $realve - $no;
							
							$uquery = "update employee_leave set remaining_leaves=".$final." where emp_id=".$empid." and leaveid=".$lvid;
							if(mysqli_query($c,$uquery)){
								
										$sql="Delete from leave_status where statid =$id";
										if(mysqli_query($c,$sql)){
							
											$emailquery = "select emailid from employee_master where emp_id=".$empid;
												if($eans=mysqli_query($c,$emailquery))
												{
													while($eresult=mysqli_fetch_object($eans))
														{
														$email = $eresult -> emailid; 
														}
												}
										
											//require 'PHPMailer-master/PHPMailerAutoload.php';
												$subject = "Leave Accepted";
								$bdy = "Hello ".$empname." Your ".$leavname." From ".$fromdate." Todate ".$todate." is Accepted";
						require 'phpmailer/PHPMailerAutoload.php';
						require 'phpmailer/class.phpmailer.php';
						require 'phpmailer/class.smtp.php';
						$mail = new PHPMailer;
						$mail -> IsSmtp();
						$mail -> SMTPDebug = 0;
						$mail -> SMTPAuth = true;
						$mail -> SMTPSecure = 'ssl';
						$mail -> Host = "smtp.gmail.com";
						$mail -> Port = 465 ;//Or 587
						$mail -> IsHTML(true);
						$mail -> Username = "yourmail@gmail.com";
						$mail -> Password = "yourpassword";
						$mail -> SetFrom("namrata.t.imca16@ahduni.edu.in");
						$mail -> Subject = $subject;
						$mail -> Body = $bdy;
						$mail -> AddAddress($email);
						
						if(!$mail -> Send()){
							echo "<script>
									alert('Mail Not Sent');
									</script>";
									echo "mail erro ".$mail->ErrorInfo;
						}else{
							echo "<script>
									alert('Mail  Sent');
									</script>";
						}
											}
								
								
							}
							
							
						}
		
					}
				
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
			
			<script type="text/javascript">
		function confirmdelete()
	{
	var s = confirm("Are you sure?");
	if(s)
	{
		return true;
	}
	else
	{
		return false;
	}
	}
</script>			
				
				
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
		
		<div class="requestpart">
		
		<br>
		
			<div class="titledata">
		
					<b>Request Page</b>
			
			</div>
			<br>
			
			<div class="displaydata" style="overflow-x:auto;">
			
			
			<table >
			<tr>
			<th>Employee  Name</th>
			<th>Category</th>
			<th>No of Leaves</th>
			<th>Type of Leave</th>
			<th>From Date</th>
			<th>To Date</th>
			</tr>
			
			<?php
			
			$fdate="";
			$tdate="";
			$cateid=0;
			$catname="";
			$empname="";
			$empid=0;
			$nofleaves=0;
			$lvid=0;
			$lvname="";
			$statid=0;
			
			
			$query="select * from leave_status";
			if($qrun=mysqli_query($c,$query))
					{
								
								while($qresult=mysqli_fetch_object($qrun))
							{
								$statid = $qresult -> statid;
								$fdate = $qresult -> from_date;
								$tdate = $qresult -> to_date;
								$nofleaves = $qresult -> no_of_leaves;
								$empid = $qresult -> emp_id;
								$cateid = $qresult -> categoryid;
								$lvid = $qresult -> leaveid;
								
								$queryt = "select emp_name from employee_master where emp_id=".$empid;
									if($qtrun=mysqli_query($c,$queryt))
										{
											while($qtresult=mysqli_fetch_object($qtrun))
												{
													$empname = $qtresult -> emp_name;
												}
										}
										
										
								$query3 = "select category_name from category_master where categoryid=".$cateid;
									if($sssf=mysqli_query($c,$query3)){
										while($aaa=mysqli_fetch_object($sssf)){
											$catname = $aaa -> category_name;
									}
					
								}
								
								
								$query2 = "select leavename from leave_master where leaveid=".$lvid;
								if($ssf=mysqli_query($c,$query2)){
								while($aa=mysqli_fetch_object($ssf)){
										$lvname = $aa -> leavename;
								}
					
								}
					?>
					
					<tr>
						<td><?php echo $empname?></td>
						<td><?php echo $catname?></td>
						<td><?php echo $nofleaves?></td>
						<td><?php echo $lvname?></td>
						<td><?php echo $fdate?></td>
						<td><?php echo $tdate?></td>
						<td><a href="requestpage.php?action=accept&id=<?php echo $statid?>&empid=<?php echo $empid?>&lname=<?php echo $lvname?>&lvid=<?php echo $lvid?>
						&nleaves=<?php echo $nofleaves?>&fdate=<?php echo $fdate?>&tdate=<?php echo $tdate?>&empname=<?php echo $empname?>" onClick="return confirmdelete()">Accept</a></td>
						
						<td><a href="requestpage.php?id=<?php echo $statid?>&empid=<?php echo $empid?>&lname=<?php echo $lvname?>
						&fdate=<?php echo $fdate?>&tdate=<?php echo $tdate?>&empname=<?php echo $empname?>&action=delete" onClick="return confirmdelete()">Reject</a></td>
					</tr>
					
					
					<?php
						}
					
					
					
					}
			
			?>
			</table>
			
			</div>
			
			<br>
			<a href="Dashboard_All.php">Click here to go to Dashboard</a>
				
		</div>
		
		
</body>
</html>
		