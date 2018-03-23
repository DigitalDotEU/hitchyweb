<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Session;

class RegisterController extends Controller
{
    public function index()
    {
        return view('client.register');
    }

    public function store(Request $request)
    {
        //create new user
        $body['name'] = Input::get('name');
        $body['firstName'] = Input::get('firstName');
        $body['lastName'] = Input::get('lastName');
        $body['email'] = Input::get('email');
        $body['city'] = Input::get('city');
        $body['country'] = Input::get('country');
        $body['about'] = Input::get('about');
        $body['password'] = Input::get('password');

        $client = new \GuzzleHttp\Client();
        try{
            $response = $client->request('POST', 'http://127.0.0.1:8080/api/register', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($body)

            ]);
        }catch (\Exception $e) {
            
            Session::flash('Error', "Something gone wrong. Please try again.");
            return Redirect::to('/index');
        }

        Session::flash('Success', "You sign up successfully.");

        return Redirect::to('/index');
    }
}
