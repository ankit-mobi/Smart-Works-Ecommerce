<!DOCTYPE html>
<html lang="en">
   <head>
    <meta charset="utf-8" />
      <title>{{$logo->name}}-User</title>
  <link rel="icon" type="image/png" href="{{url($logo->favicon)}}">
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/4ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="{{url('webstyle/owlcarousel/owl.carousel.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{url('webstyle/owlcarousel/owl.theme.default.min.css')}}">
      <link rel="stylesheet" type="text/css" href="{{url('webstyle/css/style.css')}}">
      <link href="https://fonts.googleapis.com/css?family=Philosopher&display=swap" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   </head>
   <body>

       @include('web.layout.header')
       <br><br><br>
       @yield('content')
       @include('web.layout.property')
       @include('web.layout.footer')
   </body>

   </html>


   <script src="owlcarousel/owl.carousel.min.js"></script>
<script>
   $(document).ready(function(){
     $('#owl-one').owlCarousel({
       loop:true,
       margin:10,
       autoplay:true,
       nav:true,
   
                       
   responsive: {
           0:{
               items:1
           },
           600:{
               items:1
           },
           1000:{
               items:2
           }
       }
   })
    $( ".owl-prev").html('<img src="{{url('webstyle/image/l1.PNG')}}" style="margin-left:30px;" height="55"  class="imgkl shadow">');
      $( ".owl-next").html('<img src="{{url('webstyle/image/r2.PNG')}}" style="margin-right:30px;" height="55" class="imgkl shadow">');  
   });
   
   
</script>

<style type="text/css">
.imgkl{
  background-color: white;
}
  .imgkl:hover
  {
     background: white !important;
  }
  
</style>