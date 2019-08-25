<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
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

        return view('cards.register');
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
            return response()->json([
                'message' => '',
            ], 400);
        }

        DB::transaction(function () use ($userId) {
            $card = Card::create([
                'user_id' => $userId
            ]);

        });
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
}
