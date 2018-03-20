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
        Mapper::map(52.22977, 21.01178,
            [
                'eventAfterLoad' => '',
                'eventBeforeLoad' => 'addMapStyling(map);',
                'zoom' => 14 
            ]
        );

        $client = new Client();
        $res = $client->request('GET', 'http://127.0.0.1:8080/api/points');

        $res = json_decode($res->getBody(), true);
        
        foreach($res as $point){
            $lat = $point['lattitude'];
            $lng = $point['longitude'];
            $rating = round($point['ratingSumVotes']/$point['ratingNumVotes'], 2);
            $safety = round($point['safetySumVotes']/$point['safetyNumVotes'], 2);

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
