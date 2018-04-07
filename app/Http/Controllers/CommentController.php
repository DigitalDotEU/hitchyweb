<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Session;
use Validator;
use DB; 

class CommentController extends Controller
{
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'commentBody' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            Session::flash('Error', "Please write comment content before send that.");
            return Redirect::to('/index');
        }
        
        //get info from /index add new point and send that data with post request
        $body['commentBody'] = $request->input('commentBody');
        $body['point_id'] = $request->input('point_id');
        $body['user_id'] = $token = Session::get('userID');

        $client = new \GuzzleHttp\Client();
        try{
            $response = $client->request('POST', 'http://127.0.0.1:8080/api/comment', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($body)

            ]);
        }catch (\Exception $e) {
            Session::flash('Error', "Something gone wrong. Please try again.");
            return Redirect::to('/index');
        }

        Session::flash('Success', "You added comment. Nice job!");

        return Redirect::to('/index');
    }
}
