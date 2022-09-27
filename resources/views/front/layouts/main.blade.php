<!DOCTYPE html>
<html lang="en">
<!-- head -->
<head>

@include('front/layouts/header')

</head>
<!-- end head -->

<body>
<!-- wrapper -->
<div id="wrapper">
@yield('content')
</div>
<!-- end wrapper -->

<!-- footer -->
@include('front/layouts/footer')
<!-- end footer -->
</body>

</html>