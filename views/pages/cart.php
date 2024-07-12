<div class="none popup"></div>

 <!-- Start Banner Area -->
 <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Shopping Cart</h1>
                    <nav class="d-flex align-items-center">
                        <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="category.html">Cart</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->

    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container cart-ct">
            <div class="cart_inner">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Size</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody class="cart_list">
                            
                           
                           
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12">
                <div class="blk_error"></div>
                <h3>Shipping address</h3>
                <form action="logic/addlocation.php" id="editForma" method="post" onsubmit="return addLocation()">
                          <div class="form-group my-3">
                              <label for="exampleInputEmail1">Country*</label>
                              <input type="text" class="form-control" id="country" name="country" placeholder="Serbia">
                              <input type="hidden" name="locId" value="">
                          </div>
                          <div class="form-group my-3">
                              <label for="ULName">City*</label>
                              <input type="text" class="form-control" id="city" name="city" placeholder="Belgrade">
                          </div>
                          <div class="form-group my-3">
                              <label for="UEmail">Address</label>
                              <input type="text" class="form-control" id="address" name="address" placeholder="Zdravka Čelara 11b">
                          </div>
                          
                          <button type="submit" name="addLocationCart" class="btn btn-outline-light my-3 editUser">Add location</button>
                    </form>
                    <div class="checkout_btn_inner d-flex align-items-center">
                        <a class="btn gray_btn" href="index.php?page=shop">Continue Shopping</a>
                        <a class="btn shopBtn ml-3" href="#">Proceed to checkout</a>
                    </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->