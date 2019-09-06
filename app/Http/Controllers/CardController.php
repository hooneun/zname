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

class CardController extends Controller
{
	use CardImage;

	private $message;

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index', 'show']]);
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
			$this->message = 'Card Created Over.';
			$this->index();
		}

		return view('cards.register', ['type' => 'register']);
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
			$this->message = 'Card Created Over.';
			$this->index();
		}

		$this->validator($request->all())->validate();

		$imagePaths = $this->imageCreates($userId, $request->only(Detail::$IMAGE_COLUMNS));

		$req = $request->all();

		foreach (Detail::$IMAGE_COLUMNS as $key) {
			if (empty($imagePaths[$key])) {
				$req[$key] = null;
				continue;
			}
			$req[$key] = $imagePaths[$key];
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


//		foreach (Detail::$IMAGE_COLUMNS as $image) {
//			if (!empty($card->{$image})) {
//				$path = Storage::path($card->{$image});
//				dd(Storage::mimeType($path));
//				dd(Storage::path($card->{$image}));
//				$card[$image] = base64_encode($card->{$image});
//			}
//		}

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

			foreach ($imagePaths as $_image) {
				Storage::delete($detail->{$_image});
			}

			$card->update(['title' => $req['title']]);
			unset($req['title']);

			return $detail->update($req);
		});

		return response()->json([
			compact('card')
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
		if (Card::find($id)->delete() > 0) {
			$this->message = '삭제 되었습니다';
		} else {
			$this->message = '삭제에 실패했습니다';
		}

		return redirect()->route('home', ['message' => $this->message]);
	}

	public function quickEditImage()
	{
		$id = Auth::id();

		$contents = Storage::files('/public/cards/' . $id);
		$html = '';

		if (!empty($contents)) {
			$html .= '<ul><li><a href="/rapidweaver-stack/quick-editor-admin/files/quick-edit/"> Parent Directory</a></li>';
			foreach ($contents as $content) {
				$html .= '<li><a href="' . Storage::url($content) . '"> banner-qep.jpg</a></li>';
			}
			$html .= '</ul>';
		}

		return $html;
	}

	public function getQuickEditImage($fileName)
	{
		$id = Auth::id();

		$file = Storage::get('/public/cards/' . $id . '/' . $fileName);

		dd($file);
	}

	public function quickEditImageUpload(Request $request)
	{
		Validator::make($request->all(), [
			'file' => ['required', 'image'],
		])->validate();

		$id = Auth::id();

		if (!Storage::exists('/public/cards/' . $id)) {
			Storage::makeDirectory('/public/cards/' . $id, 0775, true); //creates directory
		}

		$path = Storage::put('/public/cards/' . $id, $request->file);
		$url = Storage::url($path);

		return response()->json([
			'filePath' => $url,
		]);
	}

	public function quickEditImageDelete(Request $request)
	{
		Validator::make($request->all(), [
			'delete_file' => ['required'],
		])->validate();

		$id = Auth::id();

		Storage::delete('/public/cards/' . $id . '/' . $request->delete_file);
	}

	public function validator(array $data)
	{
		return Validator::make($data, [
			'title' => ['required', 'between:1,30'],
			'main_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
			'main_profile' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
			'name' => ['required', 'between:1,15'],
			'job' => ['required', 'between:2,15'],
			'address' => ['required', 'between:1,15'],
			'phone' => ['required', 'regex:/^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/'],
			'message' => ['nullable', 'between:5,25'],
			'email' => ['nullable', 'email'],
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
			'title' => 'ZNAME 제목',
			'main_image' => '메인 이미지',
			'main_profile' => '프로필 사진',
			'name' => '이름',
			'job' => '직업',
			'address' => '주소',
			'phone' => '연락처',
			'message' => '오늘의 한마디',
			'email' => '이메일',
			'cafe' => '카페 또는 블로그',
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
			'address' => ['required', 'between:1,15'],
			'phone' => ['required', 'regex:/^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/'],
			'message' => ['nullable', 'between:5,25'],
			'email' => ['nullable', 'email'],
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
			'title' => 'ZNAME 제목',
			'main_image' => '메인 이미지',
			'main_profile' => '프로필 사진',
			'name' => '이름',
			'job' => '직업',
			'address' => '주소',
			'phone' => '연락처',
			'message' => '오늘의 한마디',
			'email' => '이메일',
			'cafe' => '카페 또는 블로그',
		]);
	}
}
