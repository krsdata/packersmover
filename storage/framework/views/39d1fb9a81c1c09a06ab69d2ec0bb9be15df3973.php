<?php $__env->startSection('content'); ?>
  
<div class="search-overlay">
<div class="d-table">
<div class="d-table-cell">
<div class="search-overlay-layer"></div>
<div class="search-overlay-layer"></div>
<div class="search-overlay-layer"></div>
<div class="search-overlay-close">
<span class="search-overlay-close-line"></span>
<span class="search-overlay-close-line"></span>
</div>
 
</div>
</div>
</div>


<div class="hero-slider-three owl-carousel owl-theme">
<div class="hero-slider-three-item item-bg1">
<div class="d-table">
<div class="d-table-cell">
<div class="container">
<div class="row align-items-center mt-50">
<div class="col-lg-8 col-md-8">
<div class="slider-three-text"> 
<h1>Welcome To Sai Packers & Movers</h1> 
</div>
</div>
 
</div>
</div>
</div>
</div>
</div>
<div class="hero-slider-three-item item-bg2 ">
<div class="d-table">
<div class="d-table-cell">
<div class="container">
<div class="row align-items-center mt-50">
<div class="col-lg-8 col-md-8">
<div class="slider-three-text"> 
<h1>Packers & Movers In All India </h1>
  
</div>
</div>
 
</div>
</div>
</div>
</div>
</div>
<div class="hero-slider-three-item item-bg3">
<div class="d-table">
<div class="d-table-cell">
<div class="container">
<div class="row align-items-center mt-50">
<div class="col-lg-8 col-md-8">
<div class="slider-three-text"> 
<h1>Relocate Home & Office</h1>
 
</div>
</div>
 
</div>
 </div>
</div>
</div>
</div>
</div>

 

<div class="newsletter-area" style="margin-top:20px">
<div class="container">
<div class="newsletter-content">
<div class="row align-items-center">
    <div class="col-lg-3 col-sm-3 col-3">
  <div class="popup-video">
<div class="video-btn">
<a href="tel:9325744755"  >
<i class="bx bxs-phone"></i>
<span class="ripple pinkBg"></span> 
</a>
 </div>
</div> 
</div> 
    <div class="col-lg-9 col-sm-9 col-9">
<div class="newsletter-title">
 <h3> +91 9325744755  
</h3> 
<p>Shifting Domestic & Commercial   </p>
</div>
</div>

</div>
</div>
</div>
</div>
<div class="contact-form-area" style="margin-top: 75px;">
<div class="container">
<div class="section-title">
<span>Contact Us</span>
<h2>Get A Free Moving Quote</h2>
</div>
<div class="contact-form">
<form method="post">
<div class="row">
<div class="col-md-12">
<div class="form-group">
<input type="text" name="name" class="form-control" placeholder="Your name">
<div class="help-block with-errors"></div>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<input type="email" name="email" class="form-control"   placeholder="Your email address">
<div class="help-block with-errors"></div>
</div>
</div> 
<div class="col-md-6">
<div class="form-group">
<input type="text" name="phone" class="form-control"  placeholder="Your phone number">
<div class="help-block with-errors"></div>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<input type="text" name="from" class="form-control"   placeholder="From">
<div class="help-block with-errors"></div>
</div>
</div> 
<div class="col-md-6">
<div class="form-group">
<input type="text" name="to" class="form-control"   placeholder="To">
<div class="help-block with-errors"></div>
</div>
</div> 
<!--<div class="col-md-12">
<div class="form-group"> 

<select name="city"  type="text"   class="form-control" >
    
  <option value="volvo">Select City:</option>
  <option value="volvo">Within City</option>
  <option value="saab">Outside City</option> 
</select>
</div>
</div>-->
<div class="col-lg-12 col-md-12">
<div class="form-group">
<textarea name="message" type="text"  class="form-control" cols="30" rows="6"   placeholder="Write your message..."></textarea>
<div class="help-block with-errors"></div>
</div>
</div>
<div class="col-lg-12 col-md-12 text-center">
<button type="submit" name="submit" class="default-btn-one">Submit </button> 
<div class="clearfix"></div>
</div>
</div>
</form>
<?php 
if(isset($_POST['submit'])){
    $to = "saipackers90@gmail.com"; // this is your Email address
    $from = $_POST['email']; // this is the sender's Email address
    $first_name = $_POST['name'];
    $last_name = $_POST['phone'];
    $subject = "Form submission";
    $message = "Name:" . $first_name . " \n" . " Phone :" . $last_name .  " \n" . " To Address:"  . $_POST['to'].  " \n" . " From Address:"  . $_POST['from'] .  " \n" . "City:"  . $_POST['city'].  " \n" . " Message:"  . $_POST['message'];
   

    $headers = "From:" . $from;
    mail($to,$subject,$message,$headers);
   
    echo "Mail Sent. Thank you " . $first_name . ", we will contact you shortly.";
    header('Location: default.html');  
    }
?>
</div>
</div>
</div>

<div class="safe-area ptb-100"  id="about">
<div class="container">
 <div class="row align-items-center">
<div class="col-lg-6">
<div class="about-img-three">
<img src="<?php echo e(asset('front/img/ab.jpg')); ?>" alt="Image">
</div>
</div>
<div class="col-lg-6">
<div class="about-contant-others">
<div class="section-title">
<span>About Us</span>
<h2 style="color:#e25f13">Sai Packers & Movers</h2>
</div>
<div class="about-two-text">
<p style="text-align: justify;">  Sai Packers & Movers provide the best services. We believe in maintaining the good customer relation & work in eco-friedly-system because it is the most essential requirement for business growth. Our main aim is to help the customer in any manner. In providing information for office & home shifting tips, our rates are also reasonable as comparison to others. Sai Packers and Movers is Provide Door to Door service your household products without any kind of tension and damage. Sai Packers & Movers Door To Door has a secure, reliable, and more affordable way to move. It's easy and convenient. Save yourself the stress and hassle of renting, loading and driving a rental truck. Cut your labor time – only load and unload once. Move and store on your schedule. We deliver the number of Door To Door portable storage containers you need to your home or society.</p>
 
 
</div>
</div>
</div>
</div>
</div>
</div>


<div class="shipmante-area shipmante-area-bg">
<div class="container">
<div class="row align-items-center">
<div class="col-lg-7">
<div class="shipmante-text">
<h2 style="color: #221356;">Make an Easy<br> Shipment</h2>
<p  style="color: #221356;">Need help..! We are here to help you</p>
<a href="#contact">Contant Us</a>
</div>
</div>
<!--<div class="col-lg-5">
<div class="shipmante-btn">
<a href="tel:9325744755" >
<i class="bx bxs-phone-call whiteText"></i>
<span class="ripple pinkBg"></span>
<span class="ripple pinkBg"></span>
<span class="ripple pinkBg"></span>
</a>
</div>
</div>-->
</div>
</div>
</div>



<section id="services">
<div class="our-services-area ptb-100">
<div class="container">
<div class="section-title">
<span>Our Services</span>
<h2>Safe, Faster and Relaible Packers & Movers Services</h2>
</div>
<div class="row">

<div class="col-lg-4 col-md-6">
<div class="service-card-two">
<img src="<?php echo e(asset('front/img/s1.jpg')); ?>" alt="image">
<div class="service-caption">
<h3>Household shifting</h3>
<p>Household relocation services comprise of an entire packaging of goods, right from the initial inspection to the final settling in at the new destination. </p>
<a href="tel:9325744755" class="default-btn-two" style="    background: #e25f13;
    color: #fff;">Call Now</a>
</div>
</div>
</div>

<div class="col-lg-4 col-md-6">
<div class="service-card-two">
<img src="<?php echo e(asset('front/img/s22.jpg')); ?>" alt="image">
<div class="service-caption">
<h3>Office shifting</h3>
<p>Today more and more corporate firms are hiring movers and packers for the relocation purpose. Office shifting involves shifting of an entire system in an order to a new place. </p>
<a href="tel:9325744755" class="default-btn-two" style="    background: #e25f13;
    color: #fff;">Call Now</a>
</div>
</div>
</div>


<div class="col-lg-4 col-md-6">
<div class="service-card-two">
<img src="<?php echo e(asset('front/img/s3.png')); ?>" alt="image">
<div class="service-caption">
<h3>Packing & Moving Service</h3>
<p>We offers customizable packing services to fit the needs of any move. Our personal move coordinators can help you choose which option is best for you.</p>
<a href="tel:9325744755" class="default-btn-two" style="    background: #e25f13;
    color: #fff;">Call Now</a>
</div>
</div>
</div>


<div class="col-lg-4 col-md-6">
<div class="service-card-two">
<img src="<?php echo e(asset('front/img/s4.jpg')); ?>" alt="image">
<div class="service-caption">
<h3>Loading & Unloading Service</h3>
<p>We have a specialised team of trained and experienced staff who excel in handling loading and unloading services.</p>
<a href="tel:9325744755" class="default-btn-two" style="    background: #e25f13;
    color: #fff;">Call Now</a>
</div>
</div>
</div>

<div class="col-lg-4 col-md-6">
<div class="service-card-two">
<img src="<?php echo e(asset('front/img/s5.png')); ?>" alt="image">
<div class="service-caption">
<h3>Transport Services</h3>
<p>Our main motto is to provide a hassle free moving experience to those who are in need of premium and hassle free transportation services.</p>
<a href="tel:9325744755" class="default-btn-two" style="    background: #e25f13;
    color: #fff;">Call Now</a>
</div>
</div>
</div>


<div class="col-lg-4 col-md-6">
<div class="service-card-two"> 
<div class="service-caption">
<h3>Commercial Service  </h3>
<p> 
Sai Packers and Movers is dedicated to provide Commercial Services.</p>
<a href="tel:9325744755" class="default-btn-two" style="background: #e25f13;
    color: #fff;">Call Now</a>
</div>
</div>
</div>

</div>
</div>
</div>
</section>
 
<div class="frequently-area ptb-100">
<div class="container">
<div class="row align-items-center">
<div class="col-lg-6">
<div class="frequently-accrodion">
<h3>Frequently Asked Questions</h3>
<p>Have Questions?<a href="#"> Visit Our Office</a></p>
<div id="accordion">
<div class="accrodion-drawer">
<h3> How much time should I allow before contacting the moving company?
<i class='bx bx-chevron-right'></i></h3>
<div class="drawer is-hidden">
<p>24H</p>
</div>
</div>
<div class="accrodion-drawer">
<h3> Why do I need insurance if everything is packed professionally?
<i class='bx bx-chevron-right'></i></h3>
<div class="drawer is-hidden">
<p>
Items can get damaged due to unforeseen circumstances like accidents or any natural disaster in the moving process.

</p>
</div>
</div>
<div class="accrodion-drawer">
<h3> How can I move my car?
Sai Packers and Movers have special Vehicles to transport your car. Car Transportation

<i class='bx bx-chevron-right'></i></h3>
<div class="drawer is-hidden">
<p> </p>
</div>
</div>
</div>
</div>
</div>
<div class="col-lg-6">
<div class="frequently-image">
<div class="frequently-text">
<h3>Take Your Goods Anywhere Safely And on Time</h3>

<a href="tel:9325744755">+91 9325744755</a>
</div>
</div>
</div>
</div>
</div>
</div>


<div class="pt-100 pb-70" id="contact">
<div class="container">
<div class="row">
<div class="col-lg-4 col-md-6">
<div class="contact-info">
<i class='bx bxs-phone'></i>
<h4>Contact Number</h4>
<p> <a href="tel:9325744755">+91 9325744755  | 9326744755</a></p> 
</div>
</div>
<div class="col-lg-4 col-md-6">
<div class="contact-info">
<i class='bx bxs-location-plus'></i>
<h4>Our Location</h4>
<p>Sr.No.3 Shreeji Complex, Gadital, Hadapsar, Pune - 411028</p>
</div>
</div>
<div class="col-lg-4 col-md-6 offset-md-3 offset-lg-0">
<div class="contact-info">
<i class='bx bxs-envelope'></i>
<h4>Email </h4>
<p><a href="mailto:saipackers90@gmail.com"> saipackers90@gmail.com</a></p> 
</div>
</div>
</div>
</div>
</div>




<div class="newsletter-area">
<div class="container">
<div class="newsletter-content">
<div class="row align-items-center">
<div class="col-lg-9">
<div class="newsletter-title">
<h3>Save time, Save money, With Quality Packing and Moving Service
</h3> 
</div>
</div>
<div class="col-lg-3">
 <img src="<?php echo e(asset('front/img/footer.gif')); ?>" alt="logo" style="height: 130px;margin: -40px">
</div>
</div>
</div>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('front/layouts/main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/app/resources/views/front/home/home.blade.php ENDPATH**/ ?>