<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Mapper;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Session;
use Validator;

class PointController extends Controller
{
    public function index()
    {
        Mapper::map(52.22977, 21.01178,
            [
                'eventAfterLoad' => '',
                'eventBeforeLoad' => 'addMapStyling(map);',
                'zoom' => 10
            ]
        );

        $client = new Client();
        $res = $client->request('GET', 'http://127.0.0.1:8080/api/points');

        $res = json_decode($res->getBody(), true);
        
        foreach($res as $point){

            //when post is submitted
            if($point['submittedByAdmin'] == 1){
                $lat = $point['lattitude'];
                $lng = $point['longitude'];
                $rating = round($point['ratingSumVotes']/$point['ratingNumVotes'], 2);
                $safety = round($point['safetySumVotes']/$point['safetyNumVotes'], 2);
                $author = $point['author'];
    
                Mapper::marker($lat, $lng,
                [
                    'draggable' => true, 
                    'eventClick' => '
                                    $(\'.panel2\').css(\'display\', \'none\');
                                    $(\'.panel\').css(\'display\', \'block\');
                                    $(\'.panel\').empty();                
                                    $(\'.panel\').append(\'<p>' . $point['name'] . '</p>\');
                                    $(\'.panel\').append(\'<p>' . $point['description'] . '</p>\');
                                    $(\'.panel\').append(\'<p>Rating: ' . $rating . '</p>\');
                                    $(\'.panel\').append(\'<p>Safety: ' . $safety . '</p>\');
                                    $(\'.panel\').append(\'<p>Created by: ' . $author . '</p>\');
                                    $(\'.panel\').append(\'<div id="closePanel"><i class="fas fa-times"></i></div>\');
                                    $(\'#closePanel\').click(function(){ $(\'.panel\').css(\'display\', \'none\'); });
                                    '
                ]);
            }
        }
    
        $token = session(['token']);

        return view('index')->with('token', $token);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'pointName' => 'required|max:255',
            'pointDescription' => 'required|max:255',
            'rating' => 'required|integer|between:1,5',
            'safety' => 'required|integer|between:1,5'
        ]);

        if ($validator->fails()) {
            Session::flash('Error', "You put wrong data. Please check your fields.");
            return Redirect::to('/index');
        }
        
        //get info from /index add new point and send that data with post request
        $body['name'] = $request->input('pointName');
        $body['description'] = $request->input('pointDescription');
        $body['lattitude'] = $request->input('lattitude');
        $body['longitude'] = $request->input('longitude');
        $body['ratingSumVotes'] = $request->input('rating');
        $body['safetySumVotes'] = $request->input('safety');
        $body['ratingNumVotes'] = 1;
        $body['safetyNumVotes'] = 1;
        $body['submittedByAmin'] = 0;
        $body['author'] = $token = Session::get('loggedInUser');;

        $client = new \GuzzleHttp\Client();
        try{
            $response = $client->request('POST', 'http://127.0.0.1:8080/api/point', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($body)

            ]);
        }catch (\Exception $e) {
            Session::flash('Error', "Something gone wrong. Please try again.");
            return Redirect::to('/index');
        }

        Session::flash('Success', "You added point. It must by accepted by our team. Nice job!");

        return Redirect::to('/index');
    }

}
