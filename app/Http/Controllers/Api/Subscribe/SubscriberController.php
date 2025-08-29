<?php
namespace App\Http\Controllers\Api\Subscribe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Subscribe;

class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email|unique:subscribes,email',
        ]);

        try {
            Subscribe::create([
                "email"=>$request->email
            ]);
            Mail::to($request->email)->send(new WelcomeEmail());

            return $this->responseMessage('success',__('api.Successfully subscribed'));
            
        } catch (\Exception $e) {
            return $this->responseMessage('error',$e->getMessage(), null, 400);
        }
    }
}