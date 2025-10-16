<?php

session_start();

include("db_connection.php");
include("header.php");
include("nav.php");


?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Page Title #1
============================================= -->
<section id="page-title" class="page-title mt-0">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="title title-1 text-center">
                    <div class="title--content">
                        <div class="title--heading">
                            <h1>Shopping Cart</h1>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <ol class="breadcrumb">
                        <li><a href="index-2.html">Home</a></li>
                        <li class="active">Shopping Cart</li>
                    </ol>
                </div>
                <!-- .title end -->
            </div>
            <!-- .col-lg-12 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #page-title end -->

<!-- Shop Cart
============================================= -->
<section id="shopcart" class="shop shop-cart pt-0 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="cart-table table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="cart-product">
                                <th class="cart-product-item">PRODUCT NAME</th>
                                <th class="cart-product-price">UNIT PRICE</th>
                                <th class="cart-product-quantity">Quantity</th>
                                <th class="cart-product-total">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- cart product #1 -->
                            <tr class="cart-product">
                                <td class="cart-product-item">
                                    <div class="cart-product-img">
                                        <img src="assets/images/products/thumb/1.jpg" alt="product" />
                                    </div>
                                    <div class="cart-product-content">
                                        <div class="cart-product-name">
                                            <h6>Hebes Great Chair</h6>
                                        </div>
                                        <ul class="list-unstyled mb-0">
                                            <li><span>Color:</span><span>Grey</span></li>
                                            <li><span>Size:</span><span>XL</span></li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="cart-product-price">$24.00</td>
                                <td class="cart-product-quantity">
                                    <div class="product-quantity">
                                        <input class="minus" type="button" value="-">
                                        <input type="text" id="pro1-qunt" value="2" class="qty" readonly="">
                                        <input class="plus" type="button" value="+">
                                    </div>
                                </td>
                                <td class="cart-product-total">
                                    <span>$24.00</span>
                                    <div class="cart-product-remove">x</div>
                                </td>
                            </tr>
                            <!-- .cart-product end -->
                            <!-- cart product #1 -->
                            <tr class="cart-product">
                                <td class="cart-product-item">
                                    <div class="cart-product-img">
                                        <img src="assets/images/products/thumb/2.jpg" alt="product" />
                                    </div>
                                    <div class="cart-product-content">
                                        <div class="cart-product-name">
                                            <h6>Hebes Great Sofa 2019</h6>
                                        </div>
                                        <ul class="list-unstyled mb-0">
                                            <li><span>Color:</span><span>Grey</span></li>
                                            <li><span>Size:</span><span>XL</span></li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="cart-product-price">$24.00</td>
                                <td class="cart-product-quantity">
                                    <div class="product-quantity">
                                        <input class="minus" type="button" value="-">
                                        <input type="text" id="pro1-qunt" value="1" class="qty" readonly="">
                                        <input class="plus" type="button" value="+">
                                    </div>
                                </td>
                                <td class="cart-product-total">
                                    <span>$24.00</span>
                                    <div class="cart-product-remove">x</div>
                                </td>
                            </tr>
                            <!-- .cart-product end -->
                            <tr class="cart-product-action">
                                <td colspan="4">
                                    <div class="row clearfix">
                                        <div class="col-sm-12 col-md-12 col-lg-8">
                                            <a class="btn btn--secondary btn--bordered btn--rounded mr-30" href="#">CLEAR SHOPPING CART</a>
                                            <a class="btn btn--secondary btn--bordered btn--rounded" href="#">UPDATE SHOPPING CART</a>
                                        </div>
                                        <!-- .col-lg-6 end -->
                                        <div class="col-sm-12 col-md-12 col-lg-4 text-right text-left-xs text-left-sm">
                                            <a class="btn btn--secondary  btn--rounded" href="#">CONTINUE SHOPPING cart</a>
                                        </div>
                                        <!-- .col-lg-6 end -->
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- .cart-table end -->
                <div class="cart-product-action" id="applyCouponBtn">
                    <div class="cart-copoun">
                        <div class="row clearfix">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <h3>Coupon discount</h3>
                                <p>Enter your code if you have one. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
                                <form class="form-inline">
                                    <input type="text" class="form-control" id="coupon" placeholder="Enter your code here">
                                    <button type="submit" class="btn btn--secondary  btn--rounded">Apply Coupon</button>
                                </form>
                            </div>
                            <!-- .col-lg-6 end -->
                            <div class="col-sm-12 col-md-12 col-lg-5 offset-lg-1">
                                <div class="checkout--panel">
                                    <h4>Cart total</h4>
                                    <hr>
                                    <div class="sub--total">
                                        <h5>SUB TOTAL</h5>
                                        <span>$48.00</span>
                                    </div>
                                    <div class="discount-total">
                                        <span>Discount Total:</span>
                                        <span class="discount-amount">$0.00</span>
                                    </div>

                                    <div class="total">
                                        <h6>GRAND TOTAL</h6>
                                        <span>$48.00</span>
                                    </div>
                                    <p>Checkout with Mutilple Adresses</p>
                                    <a class="btn btn--primary btn--rounded btn--block" href="checkout.php">PROCEED TO CHECKOUT</a>
                                </div><!-- .checkout-panel end -->
                            </div>
                            <!-- .col-lg-6 end -->
                        </div>
                    </div>
                </div>

            </div>
            <!-- .col-lg-12 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #shopcart end -->

<?php
include("footer.php");
?>