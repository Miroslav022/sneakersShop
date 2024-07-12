
<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Edit Profile</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="category.html">Edit Profile</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row d-flex justify-content-center">
				<div class="col-lg-10">
                <div class="errors">
                                <ul>
                                    <?php if(isset($_SESSION['user']['errors'])):?>
                                        <?php foreach($_SESSION['user']['errors'] as $error):?>
                                            <li class="text-danger alert alert-danger"><?php echo $error;?></li>
                                        <?php endforeach;?>        
                                    <?php endif;?>
                                </ul>
                            </div>
                            <div class="success">
                                <?php echo isset($_GET['success']) ?  "<strong class='text-success'>".$_GET['success']."</strong>" : ''?>
                            </div>
                    <form action="models/update_user.php" method="post" enctype="multipart/form-data" onSubmit="return validateUserChanges()">
                        <div class="form-group">
                            <label for="exampleInputEmail1">First Name</label>
                            <input type="text" class="form-control" name='fName' id="fName" aria-describedby="emailHelp" placeholder="Enter First Name" value=<?=$user['first_name']?>>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Last Name</label>
                            <input type="text" class="form-control" name="lName" id="lName" aria-describedby="emailHelp" placeholder="Enter Last Name" value=<?=$user['last_name']?>>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email" value=<?=$user['email']?>>
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                                    <label for="exampleFormControlFile1">Upload <strong>Profile</strong> image</label>
                                    <input type="file" name='img' class="form-control-file" id="img">
                                    <?php if(isset($user['profile_img'])):?>
                                        <img src="assets/userImages/<?=$user['profile_img']?>" height="100px" alt="">
                                    <?php endif;?>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" name="pass" class="form-control" id="password" placeholder="Password">
                        </div>
                        <input type="hidden" name="id" value="<?=$user['user_id']?>">
                        <button type="submit" name="submitBtn" class="btn btn-primary mt-3">Edit</button>
                    </form>
                </div>
			</div>
		</div>
	</section>