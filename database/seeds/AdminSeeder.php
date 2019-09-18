<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\App\User::create([
			'name' => '관리자',
			'email' => 'admin@admin.com',
			'password' => \Illuminate\Support\Facades\Hash::make('12341234'),
			'contact_address' => '01012341234',
			'position' => '관리자',
			'address' => '',
			'admin' => true,
			'approved_at' => now()
		]);
	}
}
