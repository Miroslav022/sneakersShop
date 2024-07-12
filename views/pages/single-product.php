<?php
	$product = getSingleProduct($_GET['prodid']);
	$allSizes = getProductSizes($_GET['prodid']);
?>

<!--popup-->
<div class="popup">

</div>

<!-- Start Banner Area -->
<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Product Details Page</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="#">Shop<span class="lnr lnr-arrow-right"></span></a>
						<a href="single-product.html">product-details</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Single Product Area =================-->
	<?php $productImages=getItems('images',$_GET['prodid'])?>
	<div class="product_image_area">
		<div class="container">
			<div class="row s_product_inner">
				<div class="col-lg-6">
					<div class="s_Product_carousel">
						<div class="single-prd-item">
							<img class="img-fluid" src="assets/ProductImages/allProductImages/<?php echo $product['mainImg']?>" alt="aa">
						</div>
						<?php foreach($productImages as $img):?>
						<div class="single-prd-item">
							<img class="img-fluid" src="assets/ProductImages/allProductImages/<?php echo $img['src']?>" alt="">
						</div>
						<?php endforeach;?>
						
					</div>
				</div>
				<div class="col-lg-5 offset-lg-1">
					<div class="s_product_text">
						<h3><?php echo $product['name']?></h3>
						<h2>$ <?php echo $product['percentage']===null? number_format($product['price'], 2) : number_format($product['price']-($product['price']*$product['percentage']/100), 2) ?></h2>
						<ul class="list">
							<li><a class="active" href="#"><span>Category</span> : <?php echo $product['category']?></a></li>
							<li><a href="#"><span>Availibility</span> : <?php echo $product['quantity']>0 ? " In Stock" : " Out Stock"?></a></li>
						</ul>
						<p><?php echo $product['description']?></p>
						<div class="blk_error""></div>
						<div class="sizes">
							<ul class="d-flex">
								<?php foreach($allSizes as $size):?>
								<li class="mr-2 mb-3"><span class="btn btn-size btn-custom size" data-id=<?php echo $size['size_id']?>><?php echo $size['size']?></span></li>
								<?php endforeach;?>
							</ul>
						</div>
						<div class="product_count">
							<label for="qty">Quantity: <?php echo $product['quantity']?></label>
							<input type="number" name="qty" maxlength="12" value="1" min="1" max='<?php echo $product['quantity']?>' title="Quantity:" class="input-text qty">
						</div>
						<?php if(isset($_SESSION['user']) && $product['quantity']>0):?>
						<div class="card_area d-flex align-items-center">
							<a class="primary-btn" id="addToCart" data-uid =<?=$_SESSION['user']['user_id']?> data-id=<?=$product['product_id']?> href="#">Add to Cart</a>
							<a class="icon_btn" href="#"><i class="lnr lnr lnr-heart"></i></a>
						</div>
						<?php else:?>
							<div class="card_area d-flex align-items-center">
							<p class="text-danger">Please log in for shopping</p>
						</div>
						<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--================End Single Product Area =================-->

	<!--================Product Description Area =================-->
	<section class="product_description_area">
		<div class="container">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				
				<li class="nav-item">
					<a class="nav-link " id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
					 aria-selected="false">Specification</a>
				</li>
				
				
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
					<div class="table-responsive">
						<table class="table">
							<tbody>
								<?php $specifications = getAllSpecifications($_GET['prodid']);?>
								<?php  foreach($specifications as $spec):?>
									<tr>
									<td>
										<h5><?php echo $spec['specification']?></h5>
									</td>
									<td>
										<h5><?php echo $spec['value']?></h5>
									</td>
								</tr>
								<?php endforeach;?>	
								
								
							</tbody>
						</table>
					</div>
				</div>
				
				
			</div>
		</div>
	</section>
	<!--================End Product Description Area =================-->

