<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Mapper;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Mapper::map(53.381128999999990000, -1.470085000000040000,
            [
                'eventAfterLoad' => 'MapLoaded();',
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
                                $(\'.panel\').append(\'<p>Safety: ' . $safety . '</p>\');'
            ]);
        }
    
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
