<?php

namespace App\Http\Controllers\Auth;

use App\Card;
use App\Detail;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;

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
	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	public function agreement()
	{
		return view('auth.agreement');
	}

	public function checkAgreement(Request $request)
	{

		$this->agreementValidator($request->all())->validate();

		return redirect('register');
	}

	public function agreementValidator(array $data)
	{
		return Validator::make($data, [
			'service_agreement' => ['required', 'accepted'],
			'personal_agreement' => ['required', 'accepted']
		], [], [
			'service_agreement' => '서비스 이용약관 동의',
			'personal_agreement' => '개인정보 이용약관 동의'
		]);
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
			'contact_address' => ['required', 'string', 'max:20'],
			'company_name' => ['nullable', 'string', 'min:1', 'max:20'],
			'position' => ['nullable', 'string', 'between:2,50'],
			'address' => ['nullable', 'string', 'max:100'],
			'gender' => ['required', 'in:M,F']
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{
		$user =  User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
			'contact_address' => $data['contact_address'],
			'company_name' => $data['company_name'],
			'position' => $data['position'],
			'address' => $data['address'],
			'gender' => $data['gender']
		]);

		$card = DB::transaction(function () use ($user) {
			$card = Card::create([
				'user_id' => $user->id,
				'title' => $user->name . '님의 명함입니다.',
				'init' => true,
			]);

			$main_profile = $user->gender === 'M'
				? 'images/card/default/man.png'
				: 'images/card/default/girl.png';

			return Detail::create([
				'card_id' => $card->id,
				'main_image' => 'images/card/default/top.png',
				'main_profile' => $main_profile,
				'name' => $user->name,
				'address' => $user->address,
				'job' => $user->position,
				'phone' => $user->contact_address,
				'message' => $user->name . '님의 명함입니다.',
				'email' => $user->email,
				'ad_image_top' => 'images/card/default/bottom.png',
			]);
		});

		return $user;
	}
}
