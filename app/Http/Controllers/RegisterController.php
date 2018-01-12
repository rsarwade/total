<?php
  namespace App\Http\Controllers;
	
  use Hash;
	use App\User;
	use Illuminate\Http\Request;
	use Illuminate\Routing\Controller as BaseController;

  class RegisterController extends BaseController {
    
    public function showRegister() {
      return view('register');
    }
		
		public function doRegister(Request $request) {
			$postData = $request->all();
			
			//echo "<pre>"; print_r($request->all());
			return response()->json(['data' => $postData], 201);
			exit;
			echo 'i am here'; exit;

			$user = new User();
			
			//print_r($user);
			//exit;
			
			//$user->name = request()->name;
			// or
			//$user->name = request('first_name');
			//$user->create();
			//or
      $user->name     = 'Sunil Limboo';
			$user->email    = 'aaaa@mail.com';
			$user->role_id  = 2;
			$user->avatar   = 'users/default.png';
			$user->password = Hash::make('password');
			//$user->username = 'limbuzkid';
			//$user->password = Hash::make('password');
			$user->save();
      

		}
  }