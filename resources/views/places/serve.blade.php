@if(isset($_GET['check']))
  {{ var_dump($places->results[0]) }}
  {{ die() }}
@endif
@extends('layouts.master')

@include('layouts.distance')

@section('heading')
{{ $text['heading'] }}
@endsection

@section('sub_heading')
{{ $text['sub_heading'] }}
@endsection

@section('content')

<div class="row" style="margin-bottom: 5em">

       @foreach ($places->results as $place)
        <div class="col-md-10 offset-md-1">
        <a href="{{ url('/place-det/' . $place->place_id . '/' . $place->name . '/' . $pointers[0] . '/' . $pointers[1]) }}" class="anchor">
          <div class="card card-inverse card-success text-xs-center">
            <div class="card-block">
            <div class="row">
              <div class="col-md-6" align="center">
              <blockquote class="card-blockquote">
                <p><h4>{{ $place->name }}</h4></p>
                <footer>{{ $place->vicinity }}</footer>
              </blockquote>
              <p>
                <div id="rateYo{{ $place->place_id }}"></div>
                <script type="text/javascript">
                  
                    $("#rateYo{{ $place->place_id }}").rateYo({
                       rating: {{ isset($place->rating) ? $place->rating : 0 }},
                       starWidth: "20px"
                     });
              
                </script>
              </p>
              <p class="distance">
              <?php  $distance = distance($place->geometry->location->lat, $place->geometry->location->lng, $pointers[0], $pointers[1], "K");

                if ($distance < 1) {
                  $distance = $distance * 1000;
                  echo round($distance , 3) . " Meters away";
                }else{
                  echo round($distance, 2) . " Km away ";
                }

              ?>
              </p>
              </div>
              <div class="col-md-6" align="center">
              <p class="icon">
              <img src="{{ $place->icon }}">                
              </p>
              <p>
              @foreach ($place->types as $ele)
                <span class="tag tag-food">{{ $ele }}</span>
              @endforeach
              </p>
              </div>
            </div>
            </div>
          </div>
        </a>
        </div>
      
      @endforeach 
      
</div>

@endsection

@section('footer')

@endsection