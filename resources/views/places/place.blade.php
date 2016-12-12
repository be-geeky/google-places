@if(isset($_GET['check']))
{{ var_dump($place) }}
{{ die() }}
@endif
@extends('layouts.master')

@section('content')

@section('heading')
<img src="{{ $place->icon }}">
@endsection

@section('sub_heading')
<h2>{{ $name }}</h2>
@endsection

<div class="row hit">
    <div class="col-md-4 run">
      <p><span class="bullet">Address : </span> {{ $place->formatted_address }}</p>
      <p><span class="bullet">Phone No: </span> {{ isset($place->formatted_phone_number) ? $place->formatted_phone_number : "" }}</p>
      <p><span class="bullet">Internationl Phone No: </span>  {{ isset($place->international_phone_number) ? $place->international_phone_number : "" }}</p>
      <div id="rating"></div>
      <a href="{{ isset($place->website) ? $place->website : "#" }}" class="btn btn-success" style="margin-top: 20px">Visit Website</a>
    </div>
    <div class="col-md-8">
      <div id="floating-panel">
          <b>Mode of Travel: </b>
          <select id="mode">
            <option value="DRIVING">Driving</option>
            <option value="WALKING">Walking</option>
            <option value="BICYCLING">Bicycling</option>
            <option value="TRANSIT">Transit</option>
          </select>
       <div id="map"></div>
    </div>
  </div>
  </div>

<div class="container" style="margin-bottom: 5em">
	
	<div class="row" style="margin-top: 4em">
		<h4 class="elegant">Gallery:</h4>
	</div>
  <div class="row" style="margin-top: 4em" id="status" align="center"></div>
   @if (isset($place->photos))
  <div class="card-columns" id="images_container" style="margin-top: 20px">
  </div>
  @else
     <div class="alert alert-warning">
      There are no images on this thread.
    </div>
   @endif

  <div id="togMg_container" align="center"></div>

  <div class="row" style="margin-top: 4em">
    <h4 class="elegant">Reviews:</h4>
  </div>
  <div id="reviews_status" style="margin-top: 20px"></div>
  <div class="row" id="reviews_container" style="margin-top: 20px">

   @if (isset($place->reviews))
    {{-- expr --}}
    @foreach ($place->reviews as $review)
    {{-- expr --}}
    <div class="row" style="margin-bottom: 30px;">
      <div class="col-md-3">
        <p> 
        @if (isset($review->profile_photo_url))
          <img src="{{ $review->profile_photo_url }}" alt="" class="review-img">
          {{-- expr --}}
        @endif    
        </p>
          <a href="{{ isset($review->author_url) ? $review->author_url : "" }}"><p class="author-name">{{ $review->author_name }}</p></a>
      </div>

        <div class="col-md-6">  
    <div class="alert alert-success">
        
          <cite title="Source Title">{{ !empty($review->text) ? $review->text : "This user didnt wrote a thing. Empty minded Pricks!" }}</cite>
            
      </div>
      </div>
      <div class="col-md-3">
        <span class="pull-xs-right"><div id="rating{{ $loop->iteration }}"></div>
          <script type="text/javascript">
            $("#rating{{ $loop->iteration }}").rateYo({
               rating: {{ isset($review->rating) ? $review->rating : 0 }},
               starWidth: "20px"
             });
          </script>
        </span>          
        </div>
    </div>
  @endforeach 
  @else
    <div class="alert alert-warning">
      There are no reviews on this thread.
    </div>
  @endif 
  </div>

</div>
@endsection

@section('footer')
<script type="text/javascript">
	$("#rating").rateYo({
	   rating: {{ isset($place->rating) ? $place->rating : 0 }},
	   starWidth: "20px"
	 });
</script>
    <script>
      function initMap() {
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var directionsService = new google.maps.DirectionsService;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,
          center: {lat: {{ $lat }}, lng: {{ $lng }} }
        });
        directionsDisplay.setMap(map);

        calculateAndDisplayRoute(directionsService, directionsDisplay);
        document.getElementById('mode').addEventListener('change', function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        });
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        var selectedMode = document.getElementById('mode').value;
        directionsService.route({
          origin: {lat: {{ $lat }}, lng:  {{ $lng }} },  // Haight.
          destination: {lat: {{ $place->geometry->location->lat }}, lng:  {{ $place->geometry->location->lng }} },  // Ocean Beach.
          // Note that Javascript allows us to access the constant
          // using square brackets and a string value as its
          // "property."
          travelMode: google.maps.TravelMode[selectedMode]
        }, function(response, status) {
          if (status == 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCT85MdqO6ytsl6pXASit-Yq4G538swiGE&callback=initMap">
    </script>
     @if (isset($place->photos))
      <script type="text/javascript">
        var loading = "<img src='{{ url('img/loading.gif') }}' /><p>Loading images. Hold on!</p>";
        $("#status").html(loading);
        $.ajaxSetup({
             headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
          });
          
              $.ajax({
                      url:"{{ url('/trimImages') }}",
                      type: "post",
                      dataType: 'json',
                      data: { packet : {!! json_encode($place->photos) !!} },
                      success: function(data){
                        var images = "";
                        $.each(data,function(index, value) {
                          if (index > 2) {
                          images+="<div class='card togMg'><img class='card-img-top img-fluid gal-img' src='" + value + "'></div>";   
                          }else{
                              images+="<div class='card'><img class='card-img-top img-fluid gal-img' src='" + value + "'></div>";
                          }
                        });
                        $("#status").remove();
                        $("#images_container").html(images);
                        $(".togMg").hide();
                        $("#togMg_container").html("<button type='button' class='btn btn-success' id='show_more_togMg'>Toggle images</button>");
                        $("#show_more_togMg").click(function(){
                          $(".togMg").toggle(500);
                        });
                      },error : function() {
                        $("#status").html("<h5 class='status_message'>Uh oh! the Server is too busy or your network is fucked up!</h5>");
                      },
                  });
        
      </script>
      {{-- expr --}}
    @endif 

@endsection