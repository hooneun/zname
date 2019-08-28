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
        $this->middleware('auth', ['except' => ['index']]);
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
     * @param  \Illuminate\Http\Request  $request
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

        foreach ($imagePaths as $key => $path) {
            $req[$key] = $path;
        }

        $card = DB::transaction(function () use ($userId, $req) {
            $card = Card::create([
                'user_id' => $userId
            ]);

            $req['card_id'] = $card->id;

            return Detail::create($req);
        });

        return view('cards.register', compact('card'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
            'main_image' => ['required', 'image'],
            'main_profile' => ['required', 'image'],
            'name' => ['required'],
            'job' => ['required', 'between:2,30'],
            'address' => ['required', 'between:1, 30'],
            'phone' => ['required'],
            'message' => ['between:5,50'],
            'email' => ['email'],
            'cafe' => ['active_url'],
            'facebook' => ['active_url'],
            'twitter' => ['active_url'],
            'instagram' => ['active_url'],
            'band' => ['active_url'],
            'kakao' => ['active_url'],
            'ad_image_top' => ['image'],
            'ad_content_top' => ['between:5,200'],
            'ad_image_middle' => ['image'],
            'ad_content_middle' => ['between:5,200'],
            'ad_image_bottom' => ['image'],
            'ad_content_bottom' => ['between:5,200'],
        ],[],[
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
