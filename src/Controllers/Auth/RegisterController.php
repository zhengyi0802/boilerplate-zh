<?php

namespace Sebastienheyd\Boilerplate\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $userModel = config('auth.providers.users.model');
        $this->firstUser = $userModel::whereRoleIs('admin')->count() === 0;
    }

    /**
     * @return string
     */
    protected function redirectTo()
    {
        return route(config('boilerplate.app.redirectTo', 'boilerplate.dashboard'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'last_name'  => 'required|max:255',
            'first_name' => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'password'   => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if (! $this->firstUser && ! config('boilerplate.auth.register')) {
            abort('404');
        }

        return view('boilerplate::auth.register', ['firstUser' => $this->firstUser]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        if (! $this->firstUser && ! config('boilerplate.auth.register')) {
            abort('404');
        }

        $userModel = config('auth.providers.users.model');
        $roleModel = config('laratrust.models.role');

        $user = $userModel::withTrashed()->updateOrCreate(['email' => $data['email']], [
            'active'     => true,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
            'last_login' => Carbon::now()->toDateTimeString(),
        ]);

        if ($this->firstUser) {
            $admin = $roleModel::whereName('admin')->first();
            $user->attachRole($admin);
        } else {
            $user->restore();
            $role = $roleModel::whereName(config('boilerplate.auth.register_role'))->first();
            $user->roles()->sync([$role->id]);
        }

        return $user;
    }
}
