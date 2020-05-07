<?php
	session_start();
	include("config_project.php");
?>
<html>
	<head>
			<link rel="stylesheet" type="text/css" href="uses.css">	
			<link rel="stylesheet" type="text/css" href="registeration.css">	
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			
			
			  <script>
            $(document).ready(function () {
              
                $("#cateid").on("change", function () {

                    var cateid = $("#cateid").val();
                    alert(cateid);
                    $.ajax({
                        url: "datagal.php",
                        data: {cateid: cateid},
                        type: 'POST',
                        success: function (response)
                        {
                           
                            $("#getdta").html(response);

                        }


                    });
                });
            });
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
	
	
	<?php
				// For insertion
				if($_SERVER['REQUEST_METHOD']=="POST")
			{
					$btnname=(isset($_POST['submit_registeration']));
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
						
						
						
						
						
					}
					if($btnname == "Register")
					{
						$name = ($_POST['empname']);
						//$img = ($_FILES['file']);
						$categoryid = ($_POST['cateid']);
						$contact =($_POST['empcontact']);
						$address = ($_POST['empaddress']);
						$emailid = ($_POST['empemail']);
						$password = ($_POST['password']);
						$gender = ($_POST['gender']);
						
						
						$filepath ="img_project\\" .$_FILES["file"]["name"];
						$fname = $_FILES["file"]["name"];
						if($_FILES["file"]["size"] > 50000000){
								echo "Sorry, Your file is too large.";
							}
							else
							{
								$imageFileType =strtolower(pathinfo($filepath,PATHINFO_EXTENSION));
								//echo "Image type".$imageFileType;
									if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" && $imageFileType != "jpeg")
									{
										echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
									}
									else{
										if(move_uploaded_file($_FILES["file"]["tmp_name"],$filepath))
											{
												echo "<script>
											alert('New $filepath');
											</script>";
									
												//echo "<img src=".$filepath." height ='200' width='200'/>";
											 $query="insert into employee_master(emp_name,address,emp_img,contact,categoryid,gender,emailid,password) values('$name','$address','$fname',$contact,$categoryid,'$gender','$emailid','$password')";
											// mysqli_query($c,$query);
											 //header("location:hhh.php");
											 if(mysqli_query($c,$query))
												{
														$last_id = mysqli_insert_id($c);
													//	echo "Last id".$last_id;
														$st = "select leaveid from leave_master";
														$s1 = mysqli_query($c,$st);
														while($s2=mysqli_fetch_object($s1))
														{
																$leaveid = $s2 -> leaveid;
															//	echo "Leave id".$leaveid;
																$query="insert into employee_leave(emp_id,leaveid,remaining_leaves)values($last_id,$leaveid,30)";
															mysqli_query($c,$query);
														}
														
														header("location:Dashboard_All.php");
												}
												else
												{
													echo "Error in sql";
												}
												
											}
										else
										{
											echo "Error in img insertion";
										}
									
									}
							}
						
					}
					
					
			}
				
	
	?>
	
	<div class="body_part">
	
	
		<div class="container">
		
	<?php
	$s = "select categoryid from category_master";
	$s1 = mysqli_query($c,$s);
	$count = mysqli_num_rows($s1);
	
	?>
	
	<form name="registeration" id="registeration" method="post" enctype="multipart/form-data" >
	
	<h1 id="regisid">Registeration</h1>
	  
	 <div class="row">
		 <div class="col-25">
			Name :  </div> 
		<div class="col-75">
				<input type="text" name="empname">
			</div>
	
	</div>
	
	 <div class="row">
		 <div class="col-25">
			Select img : 
		</div>
		<div class="col-75">
		<input type="file" name="file">
		</div>
	</div>
		
	<div class="row">
		 <div class="col-25">
			 <label for="Image">Category ID</label>
		 </div>
		 <div class="col-75">
	<select name="cateid"  id="cateid">
	<?php
		while($s2=mysqli_fetch_object($s1))
		{
			$id = $s2 -> categoryid;
	?>
			<option value="<?php echo $id ?>"><?php echo $id?></option>
			
			
	<?php
		}
	?>	
		</select>
		</div>
	</div>
	
	<div class="row">
		 <div class="col-25">
		 <label for="Image">Category</label>
		 </div>
		
		 <div class="col-75" id="getdta">

          </div>
	</div>
		  <div class="row">
			 <div class="col-25">
		     Contact no:
				</div>
			<div class="col-75">
			 <input type="text" name="empcontact">
			 </div>
		 </div>
		 
		  <div class="row">
			 <div class="col-25">
			 Address: 
			 </div>
			 <div class="col-75">
			 <Textarea name="empaddress" style="height:200px"></Textarea>
			  </div>
		 </div>
			 
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
				<input type="text" name="password">
			 </div>
		 </div>
			 
			 <div class="row">
			 <div class="col-25"> 
			 Gender: 
			 </div>
				<div class="col-75">
			 <input type="radio" id="male" name="gender" value="male">
							<label for="male">Male</label>
							<input type="radio" id="female" name="gender" value="female">
							<label for="female">Female</label>
							<input type="radio" id="other" name="gender" value="other">
							<label for="other">Other</label>
							 </div>
		 </div>
			
			<div class="row">
				<input type="submit" value="Register" name="submit_registeration">
			</div>
		
		 
	</form>
	</div>
	
			<div class="rightdata">
			<img src="./img_project/signup-image" width="450px" height="450px">
			</div>
	</div>
	
	</body>
	
	
</html>
