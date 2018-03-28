<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
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
        $body['name'] = $request->input('name');
        $body['firstName'] = $request->input('firstName');
        $body['lastName'] = $request->input('lastName');
        $body['email'] = $request->input('email');
        $body['city'] = $request->input('city');
        $body['country'] = $request->input('country');
        $body['about'] = $request->input('about');
        $body['password'] = $request->input('password');

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
