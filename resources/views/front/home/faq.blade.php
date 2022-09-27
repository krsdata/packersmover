@extends('front/layouts/main')

@section('content')


        <!-- faq begin -->
        <div class="faq">
            <div class="container">
                <div class="row justify-content-between">
                  <h2 class="title">FAQ</h2>
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="faq-content">
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                    
									<?php foreach($data as $dkey => $dval) {?>
                                    <div class="single-faq">
                                        <h4><?php echo $dval['title'];?></h4>
                                        <p><?php echo strip_tags($dval['description']); ?></p>
                                    </div>
									<?php } ?>
                                   
                                </div>

                                
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- faq end -->


        @endsection