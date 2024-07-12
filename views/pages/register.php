<?php
	if(isset($user)){
		header("location: index.php");
		ob_end_flush();
        exit;
	}
?>

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Login/Register</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="category.html">Login/Register</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				
				<div class="col-lg-6">
				
					<div class="login_form_inner">
						<h3>Create account</h3>
						<div class="success"></div>
						<form class="row login_form" action="models/registerUser.php" method="post" id="contactForm" novalidate="novalidate">
							<div class="col-md-12 form-group">
                                <p class="error"></p>
								<input type="text" class="form-control" id="firstN" name="name" placeholder="First name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'First name'">
							</div>
                            <div class="col-md-12 form-group">
                            <p class="error"></p>
								<input type="text" class="form-control" id="lastN" name="name" placeholder="Last name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Last name'">
							</div>
                            <div class="col-md-12 form-group">
                            <p class="error"></p>
								<input type="text" class="form-control" id="email" name="name" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
							</div>
							<div class="col-md-12 form-group">
                            <p class="error"></p>
								<input type="password" class="form-control" id="password" name="name" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
							</div>
                            <div class="col-md-12 form-group">
                            <p class="error"></p>
								<input type="password" class="form-control" id="passwordConfirm" name="name" placeholder="Confirm password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Confirm password'">
							</div>
							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="primary-btn register-btn mt-3 mb-4">Register</button>
							</div>
						</form>
					</div>
				</div>
                <div class="col-lg-6">
					<div class="login_box_img">
						<img class="img-fluid" src="assets/img/login.jpg" alt="">
						<div class="hover">
							<h4>You alredy have account?</h4>
							<p>There are advances being made in science and technology everyday, and a good example of this is the</p>
							<a class="primary-btn" href="index.php?page=login">Log in</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->