<?php $__env->startSection('content'); ?>


        <!-- contact begin -->
        <div class="contact" id="contact">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-7 col-sm-10">
                        <div class="section-title">
                            <h2>Get in touch with us</h2>
                            <p>Misco has thousands of free online games for all generation. Play action, racing, sports, and other fun games for free at Misco</p>
                        </div>
                    </div>
                </div>
                <div class="bg-tamim">
                    <div class="row justify-content-around">
                        <div class="col-xl-6 col-lg-6 col-sm-10 col-md-6">
                            <div class="part-form">
                            <form  class="form-sample" method="POST" action = "<?php echo e(url('contact_store')); ?>"  accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                                    <?php echo e(csrf_field()); ?>

                                    <input type="text" placeholder="Name">
                                    <input type="text" placeholder="Email">
                                    <input type="text" placeholder="Mobile Number">
                                    <textarea placeholder="Message..."></textarea>                                    
                                    <button type="submit" class="submit-btn def-btn " data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"> Submit </button>
                                </form>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-sm-10 col-md-6">
                            <div class="part-address">
                                <div class="addressing">
                                    <div class="single-address">
                                        <h4>Our Office</h4>
                                        <p>3n Lotta Initial Business 
                                            Centre Wilson Business Park                           
                                            <br/>Manchester, M40 8WN UK</p>
                                    </div>
                                    <div class="single-address">
                                        <h4>Email</h4>
                                        <p>DanielleHButeau@teleworm.us<br/>
                                            CharlesTPride@armyspy.com</p>
                                    </div>
                                    <div class="single-address">
                                        <h4>Phone</h4>
                                        <p>+1 318-342-7639<br/>
                                            +1 530-259-4087</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- contact end -->

        <?php $__env->stopSection(); ?>
<?php echo $__env->make('front/layouts/main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\saipackers\resources\views/front/home/contact.blade.php ENDPATH**/ ?>