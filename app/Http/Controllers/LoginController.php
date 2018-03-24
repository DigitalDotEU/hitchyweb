<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('client.login');
    }

    public function store(Request $request)
    {
        //create new user
        $body['email'] = Input::get('email');
        $body['password'] = Input::get('password');

        $client = new \GuzzleHttp\Client();
        try{
            $response = $client->request('POST', 'http://127.0.0.1:8080/api/login', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($body)

            ]);
        }catch (\Exception $e) {
            Session::flash('Error', "Something gone wrong. Please try again.");
            return Redirect::to('/index');
        }

        $token = json_decode($response->getBody());

        //save in session logged user email to user in created new point e.g. created by radoszszymon@gmail.com
        $loggedInUser = $token->email;
        $token = $token->success->token;

        Session::put('token', $token);
        Session::put('loggedInUser', $loggedInUser);

        //dd($token);

        Session::flash('Success', "You sign in successfully.");

        return Redirect::to('/index');
    }

    public function logout()
    {
        Session::forget('token');
        Session::flash('Success', "You log out successfully.");

        return Redirect::to('/index');
    }
}
