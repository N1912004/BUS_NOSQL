<html>

<head>
	<meta charset="UTF-8">
	<title>Hệ thống xe buýt TP.HCM</title>
	<link rel="icon" type="image/x-icon" href="../../Images/icon1.png">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css">

	<script>
		// Ngăn back về trang trước
		history.pushState(null, null, null);
		window.addEventListener('popstate', function () {
			history.pushState(null, null, null);
		});
		window.history.forward();
		function noBack() { window.history.forward(); }

		// ✅ Kiểm tra dữ liệu form
		function validateForm() {
			const username = document.forms["loginForm"]["username"].value.trim();
			const password = document.forms["loginForm"]["password"].value.trim();
			const usernamePattern = /^[a-zA-Z0-9_]{3,20}$/; // chỉ cho phép ký tự chữ, số, gạch dưới

			if (username === "" || password === "") {
				alert("⚠️ Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!");
				return false;
			}
			if (!usernamePattern.test(username)) {
				alert("⚠️ Tên đăng nhập chỉ được chứa chữ, số, dấu gạch dưới và từ 3–20 ký tự!");
				return false;
			}
			if (password.length < 3) {
				alert("⚠️ Mật khẩu phải có ít nhất 3 ký tự!");
				return false;
			}
			return true;
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
		<button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks" aria-label="Bật menu">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse justify-content-between" id="navLinks">
			<ul class="navbar-nav">
				<li class="nav-item"><a href="login.php" class="nav-link">TRANG CHỦ</a></li>
				<li class="nav-item"><a href="../../about.html" class="nav-link">GIỚI THIỆU</a></li>
				<li class="nav-item"><a href="../../team.html" class="nav-link">NHÓM PHÁT TRIỂN</a></li>
				 <li class="nav-item">
					<a href="../../Conductor_DashBoard/timetable.php" class="nav-link">LỊCH TRÌNH</a>
				</li>
			</ul>
			<span class="nav-item">
				<a class="nav-link" role="button" href="login.php">Đăng nhập Tài xế / Phụ xe</a>
			</span>
		</div>
	</nav>

	<div class="container right-panel-active">
		<div class="container__form container--signup">
			<form name="loginForm" action="" class="form" method="POST" onsubmit="return validateForm()">
			<div class="form-group">
			<label for="username">Tên đăng nhập quản trị</label>
				<input type="text" name="username" class="input" placeholder="Nhập tên đăng nhập">
			</div>
			<div class="form-group">
				<labefor="password">Mật khẩu quản trị</label>
				<input type="password" name="password" class="input" placeholder="Nhập mật khẩu">
				<br>
			</div>
				<input type="submit" name="login" value="Đăng nhập Quản trị" class='btn'>
			</form>
		</div>
		<div class="container__overlay">
			<div class="overlay"></div>
		</div>
	</div>

	<script src="./script.js"></script>

	<?php
	require_once '../../Conductor_DashBoard/arangodb_connection.php';
	use ArangoDBClient\Statement as ArangoStatement;
	use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;

	if (isset($_POST['login'])) {
		session_start();

		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		// ✅ Kiểm tra lại phía server (tránh bypass JS)
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

		$documentHandler = new ArangoDocumentHandler($connection);
		$query = 'FOR u IN loginAdmin FILTER u.user_name == @username AND u.password == @password RETURN u';
		$statement = new ArangoStatement($connection, [
			'query' => $query,
			'bindVars' => [
				'username' => $username,
				'password' => $password
			]
		]);

		$cursor = $statement->execute();
		$users = $cursor->getAll();

		if (count($users) > 0) {
			echo "<script>alert('✅ Đăng nhập thành công!');</script>";
			$_SESSION['status'] = "Active";
			$_SESSION['username'] = $username;
			header("refresh:0; url=../../Conductor_DashBoard/AdminDashBoard.php");
			exit;
		} else {
			echo "<script>alert('❌ Sai tên đăng nhập hoặc mật khẩu!');</script>";
		}
	}
	?>
</body>

</html>
