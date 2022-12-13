<?php

namespace Canopy\Ecommerce\Http\Controllers\Customers;

use URL;
use Theme;
use SeoHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Canopy\Ecommerce\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Canopy\ACL\Traits\LogoutGuardTrait;
use Canopy\ACL\Traits\AuthenticatesUsers;
use Canopy\Ecommerce\Models\CustomerTalentHistory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Canopy\Ecommerce\Repositories\Interfaces\TalentInterface;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;

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

    use AuthenticatesUsers, LogoutGuardTrait;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo;

    /**
     * @var CustomerInterface
     */
    protected $customerRepository;

    /**
     * @var TalentInterface
     */
    protected $talentRepository;

    /**
     * Create a new controller instance.
     * @param CustomerInterface $customerRepository
     */
    public function __construct(CustomerInterface $customerInterface, TalentInterface $talentInterface)
    {
        $this->customerRepository = $customerInterface;
        $this->talentRepository = $talentInterface;
        $this->middleware('customer.guest', ['except' => 'logout']);

        session(['url.intended' => URL::previous()]);
        $this->redirectTo = session()->get('url.intended');
    }

    /**
     * Show the application's login form.
     *
     * @return \Response
     */
    public function showLoginForm()
    {
        SeoHelper::setTitle(__('Login'));

        Theme::breadcrumb()->add(__('Home'), url('/'))->add(__('Login'), route('customer.login'));

        return Theme::scope('ecommerce.customers.login', [], 'plugins/ecommerce::themes.customers.login')->render();
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return auth('customer');
    }

    /**
     * @param Request $request
     * @return Response|void
     * @throws ValidationException
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        $data = $request->input();
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $customer = Customer::where('email',$request->input('email'))->first();
            $customer->last_login_at = Carbon::now();
            $customer->save();
            $ctHistory = new CustomerTalentHistory();
            $ctHistory->action = 'logged_in';
            $ctHistory->description =  $customer->name . ' logged in at '. Carbon::parse($customer->last_login_at)->toDateTimeString();
            $ctHistory->customer_id = $customer->id;
            $talent = $this->talentRepository->getModel()->where('id',$customer->talent_id)->first();

            if ($talent) {
                $ctHistory->talent_id = $talent->id;
            }
            $ctHistory->save();

            $input = $request->input();
            if (isset($input['redirect'])) {
                $this->redirectTo = url($input['redirect']);
            }
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        $activeGuards = 0;
        $this->guard()->logout();

        foreach (config('auth.guards', []) as $guard => $guardConfig) {
            if ($guardConfig['driver'] !== 'session') {
                continue;
            }
            if ($this->isActiveGuard($request, $guard)) {
                $activeGuards++;
            }
        }

        if (!$activeGuards) {
            $request->session()->flush();
            $request->session()->regenerate();
        }

        return $this->loggedOut($request) ?: redirect('/');
    }
}
