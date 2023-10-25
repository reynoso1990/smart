<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Tenant\Company;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\System\Configuration as SystemConfiguration;

class LoginController extends Controller
{
     

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    // protected $maxAttempts = 1;
    // protected $decayMinutes = 1;

    protected $maxAttempts = 3;
    protected $decayMinutes = 5;


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
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $company = Company::first();

        $config = SystemConfiguration::first();
         if (!$config->use_login_global) {
            $config = Configuration::first();
         }

        $useLoginGlobal =  $config->use_login_global;

        if($company->logo){
            $background_image = asset('storage/uploads/logos/' . $company->logo);
        }else{
            $background_image = asset('logo/tulogo.png');
        }

        $login = $config->login;
        return view('tenant.auth.login', compact('company', 'login', 'useLoginGlobal', 'background_image'));
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $response = [
                'success'     => true,
                'message'     => 'Bienvenido '.$user->name.' Inicio de sesión de usuario con éxito',

            ];
        } else {

            $response = [
              'success'     => false,
              'message'     => 'Usuario No autorizado',

          ];
        }
      return response()->json($response);
    }
}
