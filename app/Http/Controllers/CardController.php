<?php

namespace App\Http\Controllers;

use App\Card;
use App\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Traits\CardImage;
use Illuminate\Support\Str;

class CardController extends Controller
{
	use CardImage;

	private $message;

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index', 'show', 'showPhone']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$userId = Auth::id();
		$cardList = Card::whereUserId($userId)
			->with('details')
			->get();

		$message = $this->message;

		return view('home', compact('cardList', 'message'));
	}

	public function showRegistrationForm()
	{
		$userId = Auth::id();
		$cardCount = Card::whereUserId($userId)
			->count();

		if ($cardCount >= 3) {
			$this->message = '최대 명함 제작 개수는 3개 입니다.';
			return $this->index();
		}

		$card = Auth::user();
		$card['phone'] = $card['contact_address'];
		$card['job'] = $card['position'];

		return view('cards.register', [
			'type' => 'register',
			'card' => $card,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$userId = Auth::id();
		$cardCount = Card::whereUserId($userId)->count();

		if ($cardCount >= 3) {
			$this->message = '최대 명함 제작 개수는 3개 입니다.';
			return $this->index();
		}

		$this->validator($request->all())->validate();

		$imagePaths = $this->imageCreates($userId, $request->only(Detail::$IMAGE_COLUMNS));

		$req = $request->all();

		foreach ($imagePaths as $key => $path) {
			$req[$key] = $path;
		}

		$card = DB::transaction(function () use ($userId, $req) {
			$card = Card::create([
				'user_id' => $userId,
				'title' => $req['title'],
			]);
			unset($req['title']);

			$req['card_id'] = $card->id;

			return Detail::create($req);
		});

		return response()->json([
			compact('card'),
			'id' => $card->id,
		], 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$card = Detail::whereId($id)
			->first();
		$type = 'view';

		return view('cards.register', compact('card', 'type'));
	}

	public function showPhone($phone)
	{
		$card = Detail::where(['phone' => $phone])
			->first();
		$type = 'view';

		return view('cards.register', compact('card', 'type'));
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$card = Detail::whereId($id)
			->first();
		$type = 'edit';

		$title = Card::whereId($card->card_id)->first('title');

		$card['title'] = $title->title;


		return view('cards.register', compact('card', 'type'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$this->updateValidator($request->all())->validate();

		$imagePaths = $this->imageCreates(Auth::id(), $request->only(Detail::$IMAGE_COLUMNS));

		$req = $request->all();

		foreach ($imagePaths as $key => $path) {
			$req[$key] = $path;
		}

		$card = DB::transaction(function () use ($imagePaths, $req, $id) {
			$detail = Detail::find($id);
			$card = Card::find($detail->card_id);

			foreach ($imagePaths as $_column => $_image) {
				Storage::delete($detail->{$_column});
			}

			$card->update(['title' => $req['title']]);
			unset($req['title']);

			return [
				'success' => $detail->update($req),
				'id' => $id
			];
		});

		return response()->json([
			compact('card'),
			'id' => $card['id'],
		]);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$card = Card::find($id);
		$detail = $card->details;

		foreach (Detail::$IMAGE_COLUMNS as $_column) {
			if (!empty($detail->{$_column})) {
				Storage::delete($detail->{$_column});
			}
		}

		if ($card->delete() > 0) {
			$this->message = '삭제 되었습니다';
		} else {
			$this->message = '삭제에 실패했습니다';
		}

		return redirect()->route('home', ['message' => $this->message]);
	}

	public function validator(array $data)
	{
		return Validator::make($data, [
			'title' => ['required', 'between:1,30'],
			'main_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
			'main_profile' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
			'name' => ['required', 'between:1,15'],
			'job' => ['required', 'between:2,15'],
			'address' => ['nullable', 'between:1, 50'],
			'phone' => ['required', 'regex:/^01([0|1|6|7|8|9]?)([0-9]{3,4})([0-9]{4})$/'],
			'message' => ['required', 'between:5,25'],
			'email' => ['required', 'email'],
			'cafe' => ['nullable', 'active_url'],
			'facebook' => ['nullable', 'active_url'],
			'twitter' => ['nullable', 'active_url'],
			'instagram' => ['nullable', 'active_url'],
			'band' => ['nullable', 'active_url'],
			'kakao' => ['nullable', 'active_url'],
			'ad_image_top' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
			'ad_content_top' => ['nullable', 'between:5,200'],
			'ad_image_middle' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
			'ad_content_middle' => ['nullable', 'between:5,200'],
			'ad_image_bottom' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
			'ad_content_bottom' => ['nullable', 'between:5,200'],
		], [], [
			'title' => '올네임 제목',
			'main_image' => '메인 이미지',
			'main_profile' => '프로필 사진',
			'name' => '이름',
			'job' => '직업',
			'address' => '주소',
			'phone' => '전화번호',
			'message' => '오늘의 한마디',
			'email' => '이메일',
			'cafe' => '카페 또는 블로그',
			'facebook' => '페이스북',
			'twitter' => '트위터',
			'instagram' => '인스타그램',
			'band' => '밴드',
			'kakao' => '카카오',
			'ad_image_top' => '광고이미지1',
			'ad_content_top' => '광고문구1',
			'ad_image_middle' => '광고이미지2',
			'ad_content_middle' => '광고문구2',
			'ad_image_bottom' => '광고이미지3',
			'ad_content_bottom' => '광고문구3',
		]);
	}

	public function updateValidator(array $data)
	{
		return Validator::make($data, [
			'title' => ['required', 'between:1,30'],
			'main_image' => ['image', 'mimes:jpeg,png,jpg,gif,svg'],
			'main_profile' => ['image', 'mimes:jpeg,png,jpg,gif,svg'],
			'name' => ['required', 'between:1,15'],
			'job' => ['required', 'between:2,15'],
			'address' => ['nullable', 'between:1, 50'],
			'phone' => ['required', 'regex:/^01([0|1|6|7|8|9]?)([0-9]{3,4})([0-9]{4})$/'],
			'message' => ['required', 'between:5,25'],
			'email' => ['required', 'email'],
			'cafe' => ['nullable', 'active_url'],
			'facebook' => ['nullable', 'active_url'],
			'twitter' => ['nullable', 'active_url'],
			'instagram' => ['nullable', 'active_url'],
			'band' => ['nullable', 'active_url'],
			'kakao' => ['nullable', 'active_url'],
			'ad_image_top' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
			'ad_content_top' => ['nullable', 'between:5,200'],
			'ad_image_middle' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
			'ad_content_middle' => ['nullable', 'between:5,200'],
			'ad_image_bottom' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
			'ad_content_bottom' => ['nullable', 'between:5,200'],
		], [], [
			'title' => ' 제목',
			'main_image' => '메인 이미지',
			'main_profile' => '프로필 사진',
			'name' => '이름',
			'job' => '직업',
			'address' => '주소',
			'phone' => '전화번호',
			'message' => '오늘의 한마디',
			'email' => '이메일',
			'cafe' => '카페 또는 블로그',
			'facebook' => '페이스북',
			'twitter' => '트위터',
			'instagram' => '인스타그램',
			'band' => '밴드',
			'kakao' => '카카오',
			'ad_image_top' => '광고이미지1',
			'ad_content_top' => '광고문구1',
			'ad_image_middle' => '광고이미지2',
			'ad_content_middle' => '광고문구2',
			'ad_image_bottom' => '광고이미지3',
			'ad_content_bottom' => '광고문구3',
		]);
	}
}
