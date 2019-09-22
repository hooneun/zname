<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
	public function __construct()
	{
	}

	public function home()
	{
		$users = User::withCount('cards')->orderBy('created_at', 'desc')->paginate(10);

		return view('admin.home', compact('users'));
	}

	public function users()
	{
		$users = User::withCount('cards')->orderBy('created_at', 'desc')->paginate(10);

		return response()->json(compact('users'));
	}

	public function userDetail($id)
	{

	}
}
