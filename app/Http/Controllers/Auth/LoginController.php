<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

//use Illuminate\Foundation\Auth\AuthenticatesUsers; //Removed for AWS Cognito
use Ellaisys\Cognito\Auth\AuthenticatesUsers; //Added for AWS Cognito

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Authenticate User
     *
     * @param Request $request
     * @return mixed
     */
    public function login(\Illuminate\Http\Request $request): mixed
    {
        $response = null;

        try {
            //Convert request to collection
            $collection = collect($request->all());

            //Authenticate with Cognito Package Trait (with 'web' as the auth guard)

            if ($response = $this->attemptLogin($collection, 'web')) {
                if ($response===true) {
                    $request->session()->regenerate();

                    return redirect(route('home'));

                       // ->intended('home');
                } else if ($response===false) {
                    return redirect()
                        ->back()
//                        ->withInput($request->only('username', 'remember'))
//                        ->withErrors([
//                            'username' => 'Incorrect username and/or password !!',
//                        ]);
                        ->with('status', 'error')
                        ->with('message', 'Incorrect username and/or password !!');

                    //$this->incrementLoginAttempts($request);
                    //
                    //$this->sendFailedLoginResponse($collection, null);
                } else {
                    return $response;
                } //End if
            } //End if
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
//                ->withInput($request->only('username', 'remember'))
//                ->withErrors(['error' => $e->getMessage()]);
                ->with('status', 'error')
                ->with('message', 'Incorrect username and/or password !!');
        } //Try-catch ends

        return $response;

    } //Function ends
} //Class ends
