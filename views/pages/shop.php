<?php
	$categories = getItems('categories');
	$brands = getItems('brands');
	$genders = getItems("genders");
?>
<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Shop Category page</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="#">Shop<span class="lnr lnr-arrow-right"></span></a>
						<a href="category.html">Fashon Category</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->
	<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-filter mt-50">
					<div class="top-filter-head">Product Filters</div>
					<div class="common-filter">
						<div class="head">Brands</div>
						<form action="#">
							<ul>
								<?php foreach($brands as $brand):?>
								<li class="filter-list"><input class="pixel-checkbox brand" type="checkbox" id="black" data-id="<?php echo $brand['brand_id']?>"><label for="black"><?php echo $brand['brand']?></label></li>
								<?php endforeach;?>
							</ul>
						</form>
					</div>
					<div class="common-filter">
						<div class="head">Categories</div>
						<form action="#">
							<ul>
								<?php foreach($categories as $category):?>
								<li class="filter-list"><input class="pixel-checkbox category" type="checkbox" id="black" data-id="<?php echo $category['category_id']?>"><label for="black"><?php echo $category['category']?></label></li>
								<?php endforeach;?>
							</ul>
						</form>
					</div>
					<div class="common-filter">
						<div class="head">Genders</div>
						<form action="#">
							<ul>
								<?php foreach($genders as $gender):?>
								<li class="filter-list"><input class="pixel-checkbox gender" type="checkbox" id="black" data-id="<?php echo $gender['gender_id']?>"><label for="black"><?php echo $gender['gender']?></label></li>
								<?php endforeach;?>
							</ul>
						</form>
					</div>
				</div>
			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">
				<!-- Start Filter Bar -->
				<div class="filter-bar d-flex flex-wrap align-items-center row">
					<div class="col-10 d-flex align-items-center">
						<div class="form-group mr-2">
							<select class="form-control" id="sort">
								<option value="desc">Price Descending</option>
								<option value="asc">Price Ascending</option>
								<option value="newest">Newest</option>
							</select>
						</div>
						
						<div class="form-group">
							<select class="perPage form-control"'>
								<option value="3">Show 3</option>
								<option value="6" selected>Show 6</option>
								<option value="9">Show 9</option>
							</select>
						</div>
					</div>
					<div class="col-2">
					<div class="pagination">
						<a href="#" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
						<a href="#" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					</div>
					</div>
				</div>
				<!-- End Filter Bar -->
				<!-- Start Best Seller -->
				<section class="lattest-product-area pb-40 category-list">
					<div class="row product-container">
						
					</div>
				</section>
				<!-- End Best Seller -->
				<!-- Start Filter Bar -->
				
				<div class="filter-bar justify-content-between d-flex flex-wrap align-items-center mb-4">
					<div class="form-group">
						<select class="perPage form-control"'>
							<option value="3">Show 3</option>
							<option value="6" selected>Show 6</option>
							<option value="9">Show 9</option>
						</select>
					</div>
					<div class="pagination">
						<a href="#" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
						<a href="#" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					</div>
				</div>
				<!-- End Filter Bar -->
			</div>
		</div>
	</div>

	