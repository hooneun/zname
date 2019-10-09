<?php

namespace App\Http\Controllers;

use App\Card;
use App\Detail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
	public function __construct()
	{
	}

	public function home()
	{
		$users = User::with(['cards'])
			->orderBy('created_at', 'desc')->paginate(10);

		return view('admin.home', compact('users'));
	}

	public function users()
	{
		$users = User::withCount('cards')->orderBy('created_at', 'desc')->paginate(10);

		return response()->json(compact('users'));
	}

	public function userDetail($id)
	{
		$user = User::find($id);

		return response()->json(compact('user'));
	}

	public function userUpdate(Request $request)
	{
		$this->validator($request->all())->validate();

		if (!empty($request->password)) {
			$update = User::where('id', $request->id)
				->update([
					'name' => $request->name,
					'password' => Hash::make($request->password),
					'contact_address' => $request->contact_address
				]);
		} else {
			$update = User::where('id', $request->id)
				->update([
					'name' => $request->name,
					'contact_address' => $request->contact_address
				]);
		}


		return response()->json(compact('update'), 200);

	}

	public function userDelete(Request $request)
	{
		if (User::find($request->id)->admin === 1) {
			return response()->json([
				'message' => '관리자는 삭제할수없습니다.'
			], 422);
		}
		$delete = User::destroy($request->id);

		return response()->json(compact('delete'), 200);
	}

	public function userCardIdGet($id)
	{
		$card = Detail::where('card_id', $id)->first('id');

		return response()->json(compact('card'), 200);
	}

	protected function validator(array $data)
	{
		return Validator::make($data, [
			'id' => ['required', 'exists:users,id'],
			'name' => ['required', 'string', 'max:255'],
			'password' => ['string', 'min:8', 'confirmed', 'nullable'],
			'contact_address' => ['string', 'max:20'],
		], [], [
			'name' => '이름',
			'password' => '비밀번호',
			'contact_address' => '휴대폰번호'
		]);
	}
}
