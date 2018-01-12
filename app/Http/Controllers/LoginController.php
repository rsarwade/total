<?php
  namespace App\Http\Controllers;
	
	use Auth;
  use Hash;
	use App\User;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Http\Request;
	use Illuminate\Routing\Controller as BaseController;

  class LoginController extends BaseController {
    
		public function doLogin(Request $request) {
			$post_data 	= $request->all();
			$credentials = array(
					'mobile'   => $post_data['mobilenumber'],
					'password' => $post_data['password'],  
			);
			if(Auth::attempt($credentials)) {
				$user_arr = array(
					"userid" 			=> Auth::id(),
					"name"				=> Auth::user()->name,
					"emailid"			=> Auth::user()->email,
					"mobilenumber"=> Auth::user()->mobile
				);
				return response()->json(['statusKey' => "0", "statusMessage" => "success", 'result' => $user_arr], 201);
			} else {
				return response()->json(['statusKey' => "1", "statusMessage" => "Failure", 'result' => ['message' => 'Mobile number/ Password wrong']], 201);
			}
		}
		
  }