<?php include ( "../inc/connect.inc.php" ); ?>
<?php 
ob_start();
session_start();
if (!isset($_SESSION['admin_login'])) {
	header("location: login.php");
	$user = "";
}
else {
	$user = $_SESSION['admin_login'];
	$result = mysqli_query($link,"SELECT * FROM admin WHERE id='$user'");
		$get_user_email = mysqli_fetch_assoc($result);
			$uname_db = $get_user_email['firstName'];
}
$pname = "";
$price = "";
$available = "";
$item = "";
$descri = "";

if (isset($_POST['signup'])) {
//declere veriable
$pname = $_POST['pname'];
$price = $_POST['price'];
$available = $_POST['available'];
$item = $_POST['item'];
$descri = $_POST['descri'];
//triming name
$_POST['pname'] = trim($_POST['pname']);

//finding file extention
$profile_pic_name = @$_FILES['profilepic']['name'];
$file_basename = substr($profile_pic_name, 0, strripos($profile_pic_name, '.'));
$file_ext = substr($profile_pic_name, strripos($profile_pic_name, '.'));

if (((@$_FILES['profilepic']['type']=='image/jpeg') || (@$_FILES['profilepic']['type']=='image/png') || (@$_FILES['profilepic']['type']=='image/gif')) && (@$_FILES['profilepic']['size'] < 1000000)) {

	$item = $item;
	if (file_exists("../image/product/$item")) {
		//nothing
	}else {
		mkdir("../image/product/$item");
	}
	
	
	$filename = strtotime(date('Y-m-d H:i:s')).$file_ext;

	if (file_exists("../image/product/$item/".$filename)) {
		echo @$_FILES["profilepic"]["name"]."Already exists";
	}else {
		if(move_uploaded_file(@$_FILES["profilepic"]["tmp_name"], "../image/product/$item/".$filename)){
			$photos = $filename;
			$result = mysqli_query($link,"INSERT INTO products(pName,price,description,available,item,picture) VALUES ('$_POST[pname]','$_POST[price]','$_POST[descri]','$_POST[available]','$_POST[item]','$photos')");
				header("Location: allproducts.php");
		}else {
			echo "Something Worng on upload!!!";
		}
		//echo "Uploaded and stored in: userdata/profile_pics/$item/".@$_FILES["profilepic"]["name"];
		
		
	}
	}
	else {
		$error_message = 'Add picture!';
	}
}
$search_value = "";

?>


<!doctype html>
<html>
	<head>
		<title>Jewelery of Life</title>
		<link rel="stylesheet" type="text/css" href="../css/style.css">
	</head>
	<body class="home-welcome-text" style="background-image: url(../image/1.jpg);">
		<div class="homepageheader">
			<div class="signinButton loginButton">
				<div class="uiloginbutton signinButton loginButton" style="margin-right: 40px;">
					<?php 
						if ($user!="") {
							echo '<a style="text-decoration: none;color: #fff;" href="logout.php">LOG OUT</a>';
						}
					 ?>
					
				</div>
				<div class="uiloginbutton signinButton loginButton">
					<?php 
						if ($user!="") {
							echo '<a style="text-decoration: none;color: #fff;" href="login.php">Hi '.$uname_db.'</a>';
						}
						else {
							echo '<a style="text-decoration: none;color: #fff;" href="login.php">LOG IN</a>';
						}
					 ?>
				</div>
			</div>
			<div style="float: left; margin: 5px 0px 0px 23px;">
				<a href="index.php">
					<img style=" height: 75px; width: 130px;object-fit: contain;" src="../image/ring-logo.jpg">
				</a>
			</div>
			<div id="srcheader">
				<form id="newsearch" method="get" action="search.php">
				        <?php 
				        	echo '<input type="text" class="srctextinput" name="keywords" size="21" maxlength="120"  placeholder="Search Here..." value="'.$search_value.'"><input type="submit" value="search" class="srcbutton" >';
				         ?>
				</form>
			<div class="srcclear"></div>
			</div>
		</div>
	<?php include ( "../categories/adminmenu.php" ); ?>
		<?php 
			if(isset($success_message)) {echo $success_message;}
			else {
				echo '
					<div class="holecontainer" style="float: right; margin-right: 36%; padding-top: 20px;">
						<div class="container">
							<div>
								<div>
									<div class="signupform_content">
										<h2>Add Product Form!</h2>
										<div class="signup_error_msg">';
											if (isset($error_message)) {echo $error_message;}
										echo '</div>
										<div class="signupform_text"></div>
										<div>
											<form action="" method="POST" class="registration" enctype="multipart/form-data">
												<div class="signup_form">
													<div>
														<td >
															<input name="pname" id="first_name" placeholder="Product Name" required="required" class="first_name signupbox" type="text" size="30" value="'.$pname.'" >
														</td>
													</div>
													<div>
														<td >
															<input name="price" id="last_name" placeholder="Price" required="required" class="last_name signupbox" type="text" size="30" value="'.$price.'" >
														</td>
													</div>
													<div>
														<td>
															<input name="available" placeholder="Available Quantity" required="required" class="email signupbox" type="text" size="30" value="'.$available.'">
														</td>
													</div>
													<div>
														<td >
															<input name="descri" id="first_name" placeholder="Description" required="required" class="first_name signupbox" type="text" size="30" value="'.$descri.'" >
														</td>
													</div>
													<div>
														<td>
															<select name="item" required="required" style=" font-size: 20px;
														font-style: italic;margin-bottom: 3px;margin-top: 0px;padding: 14px;line-height: 25px;border-radius: 4px;border: 1px solid #169E8F;color: #169E8F;margin-left: 0;width: 300px;background-color: transparent;" class="">
																<option selected value="ring">Ring</option>
																<option value="earring">Earrings</option>
																<option value="necklace">Necklace</option>
																<option value="bracelet">Bracelet</option>
																<option value="tikka">Tikka</option>
																<option value="anklet">Anklet</option>
															</select>
														</td>
													</div>
													<div>
														<td>
															<input name="profilepic" class="password signupbox" type="file" value="Add Pic">
														</td>
													</div>
													<div>
														<input name="signup" class="uisignupbutton signupbutton" type="submit" value="Add Product">
													</div>
												</div>
											</form>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				';
			}

		 ?>
	</body>
</html>