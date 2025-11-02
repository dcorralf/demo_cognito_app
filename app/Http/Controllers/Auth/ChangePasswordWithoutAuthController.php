<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Ellaisys\Cognito\Auth\ChangePasswords as CognitoChangePasswords;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ChangePasswordWithoutAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */

    use CognitoChangePasswords;

    /**
     * Where to redirect users when the intended url fails.
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
//        $this->middleware('auth');
    }


    /**
     * Action to update the user password
     *
     * @param Request $request
     */
    public function actionChangePassword(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        if(session()->has('force_email')) {
            session()->forget('force_email');
        }

        try
        {
            // Validate request
            $validator = Validator::make($request->all(), [
                // It could be a username or email
                'email'    => 'required',
                'password' => 'required',
                'new_password'  => 'required|confirmed',
                'new_password_confirmation' => 'required',
            ]);

            $validator->validate();

            // Check the password
            if ($this->reset($request)) {
                //Logout on success
                auth()->guard()->logout(true);
                $request->session()->invalidate();

                return redirect(route('login'))->with('success', true);
            } else {
                return redirect()->back()
                    ->with('status', 'error')
                    ->with('message', 'There was a problem while resetting your password.');
            } //End if
        } catch(Exception $e) {
            $message = 'There was a problem while resetting your password.';
            if ($e instanceof ValidationException) {
                throw $e;
            } else if ($e instanceof CognitoIdentityProviderException) {
                $message = $e->getAwsErrorMessage();
            }
//            else {
//                //Do nothing
//            } //End if

            return redirect()->back()
//            return redirect(route('login'))
//                ->withInput($request->only('email'))
                ->with('status', 'error')
                ->with('message', $message);

        } //Try-catch ends
    }
}
