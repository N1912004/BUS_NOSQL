<html>

<head>
	<meta charset="UTF-8">
	<title>Hệ Thống Quản Lý Xe Buýt</title>
	<link rel="icon" type="image/x-icon" href="../../Images/icon1.png">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<link rel="stylesheet" href="style.css">
	<script> //chặn quay lại sau khi đăng nhập
		history.pushState(null, null, null);
		window.addEventListener('popstate', function () {
			history.pushState(null, null, null);
		});
		window.history.forward();
		function noBack() {
			window.history.forward();
		}
	</script>
	<title>Đăng nhập</title>
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
				<li class="nav-item">
					<a href="login.php" class="nav-link">Trang chủ</a>
				</li>
				<li class="nav-item">
					<a href="../../about.html" class="nav-link">Giới thiệu</a>
				</li>
				<li class="nav-item">
					<a href="../../team.html" class="nav-link">Nhóm phát triển</a>
				</li>
				<li class="nav-item">
					<a href="../../Conductor_DashBoard/timetable.php" class="nav-link">Lịch trình</a>
				</li>
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
				<input type="text" name="username" class="input" placeholder="Nhập mã số Lơ xe" required>
				<input type="password" class="input" name="password" placeholder="Nhập mật khẩu" required><br>
				<input type="submit" name="loginC" value="Đăng nhập" class='btn'></input>
			</form>
		</div>

		<!-- Đăng nhập Tài xế -->
		<div class="container__form container--signin">
			<form action="" method="POST" class='form'>
				<input type="text" name="username" class="input" placeholder="Nhập mã số Tài xế" required>
				<input type="password" name="password" class="input" placeholder="Nhập mật khẩu" required><br>
				<input type="submit" name="loginD" value="Đăng nhập" class='btn'>
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

	<!-- partial -->
	<script src="./script.js"></script>

	<!-- Optional JavaScript -->
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
		$(function () {
			$(document).scroll(function () {
				var $nav = $("#mainNavbar");
				$nav.toggleClass("scrolled", $(this).scrollTop() > $nav.height());
			});
		});
	</script>
</body>

</html>
