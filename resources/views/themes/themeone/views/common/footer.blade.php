<footer class="footer-content">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-4 col-sm-12 footer_left" style="">
                <div class="">
                    <div class="row">						
						<div class="col-12 col-md-12 col-lg-12 ft_left_in1">
							<div class="footer-contact">
								<h3>CONTACT US</h3>
								<p class="phone">
									<i class="fa fa-phone" aria-hidden="true"></i> 
									<a href="tel:1300503013">PH: 1300 503 013</a></p>
								<p class="address">
									<strong>Site Address: </strong><br>
									108 Bridge Road <br>
									Glebe, NSW 2037 
								</p>
								<p class="address">
									<strong>Postal Address: </strong><br>
									Suite 72, 30 Denison Street, <br>
									Bondi Junction NSW 2022
								</p>
								<p class="mobile_img"><img class="img-fluid" src="{{asset('').'public/images/footer-image.svg'}}" alt="icon"></p>
							</div>
						</div>
                    </div>
                </div>
            </div>
			<div class="col-12 col-lg-4 col-sm-12 footer_left" style="">
				<div class="footer_menu">
	                <div class="">                	
				    	<div class="row">
							<div class="col-12  col-md-12 col-lg-12">
								<h3>QUICK LINKS</h3>
							</div>
				    	</div>   
	                    <div class="row">
							<div class="col-12">
								<ul class="contact-list  pl-0 mb-0">
									<!-- <li><a href="#">Customer Service</a></li> -->
									<li><a href="{{ URL::to('/contact-us')}}">Contact Us</a></li>
									<li><a href="{{ URL::to('/page?name=privacy-policy')}}">Privacy & cookies</a></li>
									<li><a href="{{ URL::to('/page?name=terms-and-conditions')}}">Terms & conditions</a></li>
								</ul>
							</div>                     
	                    </div>
	                </div>
                </div>
            </div>
			<div class="col-12 col-lg-4 col-sm-12 footer_left" style="">
				<div class="footer_menu">
	                <div class="">                	
				    	<div class="row">
							<div class="col-12  col-md-12 col-lg-12">
								<h3>MY ACCOUNT</h3>
							</div>
				    	</div>   
	                    <div class="row">
							<div class="col-12  col-md-12 col-lg-12"> 
								<ul class="contact-list  pl-0 mb-0">
									{{-- <li><a href="{{ URL::to('/signup')}}">Register Account</a></li> --}}
									<li><a href="{{ URL::to('/login')}}">Login</a></li>
								</ul>
							</div>                     
	                    </div>
	                </div>
                </div>
            </div>
        </div>		
    </div>
</footer>

<div class="copyright_bottom">
	<div class="container">
		<div class="row">
			<div class="col-12 col-lg-8 col-sm-12">© Copyright <?php echo date('Y') ?> NRG INDIGENOUS PTY LTD | 
			<a href="https://www.sparkinteract.com.au/website-design-sydney/" target="_blank">Website Design</a> Sydney Spark Interact.</div>
			<div class="col-12 col-lg-4 col-sm-12">
				<ul class="contact-list pl-0 mb-0 text-right1 float-right">
					<li>ABN 56 633 957 954 | ACN 633 957 954</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!--notification-->
<div id="message_content"></div>


<!--- loader content --->
<div class="loader" id="loader">
	<!-- <img src="{{asset('').'public/images/loader.gif'}}"> -->
	<img src="{{asset('').'public/images/loader_nrg.gif'}}">
</div>
