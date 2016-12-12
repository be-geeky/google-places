<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
	// Geolocation API KEY: AIzaSyCr9lJsY-PhldW7QvpnAprrJoGdvPoyQMM
	// MAPS API KEY: AIzaSyCT85MdqO6ytsl6pXASit-Yq4G538swiGE
	// PLACES API KEY: AIzaSyAFb40nLkMTcwPiIA4tzviPZ-PEacBHrs4

    public function home()
    {
    	$geolocation = \Curl::to('https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyCr9lJsY-PhldW7QvpnAprrJoGdvPoyQMM')->post();

    	$location = json_decode($geolocation);
  
        return view('places.home', ['location' => $location]);
    }

    public function serve($type, $lat, $lng)
    {
    	$keyword = $this->keywords($type);

    	$response = \Curl::to('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=' . $lat . ',' . $lng . '&radius=1000&types=' . $keyword . '&key=AIzaSyAFb40nLkMTcwPiIA4tzviPZ-PEacBHrs4')->get();
        $service = json_decode($response);
        $text = $this->text_markers($type);
        return view('places.serve', [ 'places' => $service , 'pointers' => [$lat,$lng], 'text' => $text]);
    }

    public function serveQuery(Request $request)
    {
        $p = $request->all();
        $lat = $p['lat'];
        $lng = $p['lng'];

        $response = \Curl::to('https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=' . $lat . ',' . $lng . '&radius=50000&type=' . $p['q'] . '&key=AIzaSyAFb40nLkMTcwPiIA4tzviPZ-PEacBHrs4')->get();
        $service = json_decode($response);
        $text = [
        'heading' => $p['q'],
        'sub_heading' => 'A vague list of ' . $p['q'] . ' around you.',
        ];
        return view('places.serve', [ 'places' => $service , 'pointers' => [$lat,$lng], 'text' => $text]);
    }

    public function placeDet($id, $name , $lat , $lng)
    {
        $response = \Curl::to('https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $id . '&key=AIzaSyAFb40nLkMTcwPiIA4tzviPZ-PEacBHrs4')->get();
        $service = json_decode($response);

        return view('places.place', [ 'place' => $service->result , 'name' => $name , 'lat' => $lat , 'lng' => $lng]);
    }

    public function keywords($type)
    {
    	switch ($type) {
    		case 'rst':
    			return "restaurant";
    			break;
    		case 'hsp':
    			return "hospital";
    			break;
    		case 'bs':
    			return "bus";
    			break;		
    	}
    }

    public function text_markers($type)
    {
        switch ($type) {
            case 'rst':
                return [
                'heading' => 'Restaurants',
                'sub_heading' => 'A vague list of restaurants around you.'
                ];
                break;
            case 'hsp':
                return [
                'heading' => 'Hospitals',
                'sub_heading' => 'A vague list of Hospitals around you.'
                ];
                break;    
            case 'bs':
                return [
                'heading' => 'Random Places',
                'sub_heading' => 'A vague list of Random Places around you.'
                ];
                break;
        }
    }

    public function trimImages()
    {


        if (isset($_POST['packet'])) {
            $service = $_POST['packet'];
            $photos_array = [];

            for ($i=0; $i < count($service); $i++) {     
            $get_photos = \Curl::to('https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=' . $service[$i]['photo_reference'] . '&key=AIzaSyAFb40nLkMTcwPiIA4tzviPZ-PEacBHrs4')->get(); 
            $photos_array[$i] = $get_photos;
            }

            for ($i=0; $i < count($photos_array) ; $i++) {
                $split = explode('HREF="', $photos_array[$i]);
                $splitter = explode('">here', $split[1]); 
                $photos_array[$i] = $splitter[0];
            }

            echo json_encode($photos_array,JSON_UNESCAPED_SLASHES);
        }
    }
}



// content-type" content="text/html;charset=utf-8">
// <TITLE>302 Moved</TITLE></HEAD><BODY>
// <H1>302 Moved</H1>
// The document has moved 139 26
// <A HREF="https://lh4.googleusercontent.com/-Vx2v9F7Uc7U/VUY_g2UMnhI/AAAAAAAAAF0/YnIgTDsSwYk4JbwhxFINLzGqATH54apOA/s1600-w400/">here</A>.
// </BODY></HTML>
