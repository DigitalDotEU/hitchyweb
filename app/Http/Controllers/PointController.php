<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Mapper;

class PointController extends Controller
{
    public function index()
    {
        Mapper::map(53.381128999999990000, -1.470085000000040000,
            [
                'eventAfterLoad' => '',
                'eventBeforeLoad' => 'addMapStyling(map);'
            ]
        );

        $client = new Client();
        $res = $client->request('GET', 'http://127.0.0.1:8080/api/points');

        $res = json_decode($res->getBody(), true);
        
        foreach($res as $point){
            $lat = $point['lattitude'];
            $lng = $point['longitude'];
            $rating = $point['ratingSumVotes']/$point['ratingNumVotes'];
            $safety = $point['safetySumVotes']/$point['safetyNumVotes'];

            Mapper::marker($lat, $lng,
            [
                'draggable' => true, 
                'eventClick' => '
                                $(\'.panel\').css(\'display\', \'block\');
                                $(\'.panel\').empty();                
                                $(\'.panel\').append(\'<p>' . $point['name'] . '</p>\');
                                $(\'.panel\').append(\'<p>' . $point['description'] . '</p>\');
                                $(\'.panel\').append(\'<p>Rating: ' . $rating . '</p>\');
                                $(\'.panel\').append(\'<p>Safety: ' . $safety . '</p>\');
                                $(\'.panel\').append(\'<div id="closePanel"><i class="fas fa-times"></i></div>\');
                                $(\'#closePanel\').click(function(){ $(\'.panel\').css(\'display\', \'none\'); });
                                '
            ]);
        }
    
        $token = session(['token']);

        return view('index')->with('token', $token);
    }

}
