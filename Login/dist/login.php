<html>

<head>
	<meta charset="UTF-8">
	<title>Quản lý Xe Buýt TP.HCM</title>
	<link rel="icon" type="image/x-icon" href="../../Images/icon1.png">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<link rel="stylesheet" href="style.css">
	<script> 
		// Ngăn người dùng quay lại trang trước sau khi đăng nhập
		history.pushState(null, null, null);
		window.addEventListener('popstate', function () {
			history.pushState(null, null, null);
		});
		window.history.forward();
		function noBack() {
			window.history.forward();
		}
	</script>
</head>

<body onLoad="noBack();">
	<nav id="mainNavbar" class="navbar navbar-light navbar-expand-md py-1 px-2 fixed-top"
		style="background-color: #0cb2f9;">
		<a class="navbar-brand" href="#">
			<img src="../../Images/icon1.png" width="45" height="35" class="d-inline-block align-middle" alt="">
			 Xe Buýt TP.HCM
		</a>

		<button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse justify-content-between" id="navLinks">
			<ul class="navbar-nav">
				<li class="nav-item"><a href="login.php" class="nav-link">TRANG CHỦ</a></li>
				<li class="nav-item"><a href="../../about.html" class="nav-link">GIỚI THIỆU</a></li>
				<li class="nav-item"><a href="../../team.html" class="nav-link">NHÓM PHÁT TRIỂN</a></li>
				<li class="nav-item"><a href="../../Conductor_DashBoard/timetable.php" class="nav-link">LỊCH TRÌNH</a></li>
			</ul>

			<span class="nav-item">
				<a class="nav-link" role="button" href="adminlogin.php">Đăng nhập Quản trị</a>
			</span>
		</div>
	</nav>

	<div class="container right-panel-active">
		<!-- Đăng nhập Lơ xe -->
		<div class="container__form container--signup">
			<form action="" class="form" method="POST">
				<label for="username">Mã lơ xe</label>
				<input 
					type="text" 
					name="username" 
					class="input" 
					placeholder="Nhập mã Lơ xe"
					value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
				>
				<label for="password">Mật khẩu</label>
				<input 
					type="password" 
					class="input" 
					name="password" 
					placeholder="Nhập mật khẩu"
					value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') : ''; ?>"
				><br>

				<input type="submit" name="loginC" value="Đăng nhập" class="btn">
			</form>
		</div>

		<!-- Đăng nhập Tài xế -->
		<div class="container__form container--signin">
			<form action="" method="POST" class="form">
				<label for="username">Mã tài xế</label>
				<input 
					type="text" 
					name="username" 
					class="input" 
					placeholder="Nhập mã Tài xế"
					value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
				>
				<label for="password">Mật khẩu</label>
				<input 
					type="password" 
					name="password" 
					class="input" 
					placeholder="Nhập mật khẩu"
					value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') : ''; ?>"
				><br>

				<input type="submit" name="loginD" value="Đăng nhập" class="btn">
			</form>
		</div>

		<div class="container__overlay">
			<div class="overlay">
				<div class="overlay__panel overlay--left">
					<button class="btn" id="signIn">Đăng nhập Tài xế</button>
				</div>
				<div class="overlay__panel overlay--right">
					<button class="btn" id="signUp">Đăng nhập Lơ xe</button>
				</div>
			</div>
		</div>
	</div>

	<script src="./script.js"></script>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../Conductor_DashBoard/arangodb_connection.php';

use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;

$documentHandler = new ArangoDocumentHandler($connection);

session_start();

if (isset($_POST['loginC'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (empty($username) || empty($password)) {
		echo "<script>alert('⚠️ Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!');</script>";
		exit;
	}

	if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
		echo "<script>alert('⚠️ Tên đăng nhập không hợp lệ!');</script>";
		exit;
	}

	if (strlen($password) < 3) {
		echo "<script>alert('⚠️ Mật khẩu quá ngắn!');</script>";
		exit;
	}

	$query = 'FOR u IN login FILTER u.username == @username AND u.password == @password RETURN u';
	$statement = new ArangoStatement($connection, [
		'query' => $query,
		'bindVars' => ['username' => $username, 'password' => $password]
	]);

	$cursor = $statement->execute();
	if ($cursor->getCount() == 1) {
		$_SESSION['status'] = "Active";
		$_SESSION['username'] = $username;
		echo "<script>
			alert('✅ Đăng nhập thành công!');
			window.location.href='../../Conductor_DashBoard/conductorDashboard.php';
		</script>";
		exit;
	} else {
		echo "<script>alert('❌ Sai mã hoặc mật khẩu!');</script>";
	}
}

if (isset($_POST['loginD'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (empty($username) || empty($password)) {
		echo "<script>alert('⚠️ Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!');</script>";
		exit;
	}

	if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
		echo "<script>alert('⚠️ Tên đăng nhập không hợp lệ!');</script>";
		exit;
	}

	if (strlen($password) < 3) {
		echo "<script>alert('⚠️ Mật khẩu quá ngắn!');</script>";
		exit;
	}

	$query = 'FOR u IN loginDriver FILTER u.username == @username AND u.password == @password RETURN u';
	$statement = new ArangoStatement($connection, [
		'query' => $query,
		'bindVars' => ['username' => $username, 'password' => $password]
	]);

	$cursor = $statement->execute();
	if ($cursor->getCount() == 1) {
		$_SESSION['status'] = "Active";
		$_SESSION['username'] = $username;
		echo "<script>
			alert('✅ Đăng nhập thành công!');
			window.location.href='../../Conductor_DashBoard/Driver_DashBoard.php';
		</script>";
		exit;
	} else {
		echo "<script>alert('❌ Sai mã hoặc mật khẩu!');</script>";
	}
}
?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
	integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
	crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
	integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
	crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
	integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
	crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
	$(document).scroll(function () {
		var $nav = $("#mainNavbar");
		$nav.toggleClass("scrolled", $(this).scrollTop() > $nav.height());
	});
});
</script>

</body>
</html>
