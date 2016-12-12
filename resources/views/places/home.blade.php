<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Places</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ url('css/bootstrap4.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ url('css/home.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>

  <body>
      <nav class="navbar navbar-fixed-top navbar-dark bg-green">
      <a class="navbar-brand" href="#">Places</a>
      <ul class="nav navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
      </ul>
    </nav>
    <div id="map"></div>
    <div class="container">

      <div class="starter-template">
        <h1>Your lookup Assistant</h1>
        <p class="lead">This app exists only to allow you search all the possible places around you.</p>
      </div>

      <div class="row" style="margin: 0px 0px 50px 0px;">
        <div class="col-md-10 offset-md-1">
            <div id="imaginary_container"> 
                <div class="input-group stylish-input-group">
                  <form action="{{ url('/query') }}" method="post" accept-charset="utf-8">
                    {{ csrf_field() }}
                    <input type="text" class="form-control" name="q" placeholder="search any place.." style="height: 50px">
                    <input type="hidden" name="lat" value="{{ $location->location->lat }}">
                    <input type="hidden" name="lng" value="{{ $location->location->lng }}">
                  </form>
                </div>
            </div>
        </div>
  </div>

      <div class="row">
      <div class="col-md-4">
      <a href="{{ url('serve/rst' . "/" . $location->location->lat . "/" . $location->location->lng) }}" class="anchor">
        <div class="card card-inverse card-success text-xs-center">
          <div class="card-block">
            <blockquote class="card-blockquote">
              <p><i class="fa fa-cutlery fa-4x" aria-hidden="true"></i></p>
              <footer><h3>Restaurant</h3></footer>
            </blockquote>
          </div>
        </div>
      </a>
      </div>

      <div class="col-md-4">
      <a href="{{ url('serve/hsp' . "/" . $location->location->lat . "/" . $location->location->lng) }}" class="anchor">      
        <div class="card card-inverse card-success text-xs-center">
          <div class="card-block">
            <blockquote class="card-blockquote">
              <p><i class="fa fa-h-square fa-4x" aria-hidden="true"></i></i></p>
              <footer><h3>Hospitals</h3></footer>
            </blockquote>
          </div>
        </div>
        </a>
      </div>

      <div class="col-md-4">
      <a href="{{ url('serve/bs' . "/" . $location->location->lat . "/" . $location->location->lng) }}" class="anchor">      
        <div class="card card-inverse card-success text-xs-center">
          <div class="card-block">
            <blockquote class="card-blockquote">
              <p><i class="fa fa-bus fa-4x" aria-hidden="true"></i></p>
              <footer><h3>Bus Stops</h3></footer>
            </blockquote>
          </div>
        </div>
        </a>
      </div>
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
     <script>
      function initMap() {
        var uluru = {lat: {{ $location->location->lat }}, lng: {{ $location->location->lng }}};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCT85MdqO6ytsl6pXASit-Yq4G538swiGE&callback=initMap">
    </script>
  </body>
</html>
