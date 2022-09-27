
@extends('front/layouts/main')

@section('content')

   

        <!-- blog details begin -->
        <div class="blog-details">
            <div class="container">
                <div class="part-post">
                    <div class="part-img">
                        <img src="assets/img/news/blog-details.jpg" alt="">
                    </div>
                    <div class="part-text">
                        <h2 class="title"><?php echo $data[0]['name'];?></h2>
                      <p><?php  echo $content = strip_tags($data[0]['description']);?></p>
                    </div>
                </div>
              
     

        @endsection