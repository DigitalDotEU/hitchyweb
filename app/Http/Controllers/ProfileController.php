<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Session;

class ProfileController extends Controller
{
    public function index(){
        //create new user 
        $token = Session::get('token');

        $authorization = 'Bearer ' . $token;

        $client = new \GuzzleHttp\Client();

        $address = $this->apiAddress . '/api/get-details';

        try{
            $response = $client->request('POST', $address, [
                'headers' => ['Accept' => 'application/json', 'Authorization' => $authorization],
            ]);   
        }catch (\Exception $e) {
            Session::flash('Error', "Something gone wrong. Please try again.");
            return Redirect::to('/index');
        }    

        $user_info = json_decode($response->getBody());
        $user_info = $user_info->success;

        //dd($user_info);

        return view('client.profile')->with('user_info', $user_info);
    }
}
