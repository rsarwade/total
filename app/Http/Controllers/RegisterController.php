<?php
  namespace App\Http\Controllers;
	
  use Hash;
	use App\User;
	use App\UserProfile;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Http\Request;
	use Illuminate\Routing\Controller as BaseController;

  class RegisterController extends BaseController {
    
    public function showRegister() {
      return view('register');
    }
		
		public function doRegister(Request $request) {
			
			$error 			= false;
			$err_arr		= array();
			$post_data 		= $request->json()->all();
			$action 		= $post_data['action'];
			$name			= $post_data['username'];
			$password		= 'password';
			$social_type 	= $post_data['social_type'];
			$social_id 		= $post_data['socialid'];
			$email 			= $post_data['emailid'];
			$mobile 		= $post_data['mobilenumber'];
			$device_id 		= $post_data['deviceid'];
			
			$user = User::where('email',  $email)->count();
			if($user > 0) {
				$error = true;
				$err_arr[] = 'Email '.$email. ' already exists.'; 
			}
			$user = User::where('mobile',  $mobile)->count();
			if($user > 0) {
				$error = true;
				$err_arr[] = 'Mobile Number '.$mobile. ' already exists.'; 
			}

			if(!isset($post_data['country_id']) || empty($post_data['country_id'])){
				$error = true;
				$err_arr[] = 'Country is empty';
			}

			if(!isset($post_data['state_id']) || empty($post_data['state_id'])){
				$error = true;
				$err_arr[] = 'State is empty';
			}

			if(!isset($post_data['city_id']) || empty($post_data['city_id'])){
				$error = true;
				$err_arr[] = 'City is empty';
			}

			if(!$error) {
				$user = new User();
				$user->name     = $name;
				$user->email    = $email;
				$user->role_id  = 2;
				$user->avatar   = 'users/default.png';
				$user->password = Hash::make($password);
				$user->mobile 	= $mobile;
				
				if($user->save()) {
					// call sms service here
					// $otp =
					$userprofile = new UserProfile();
					$userprofile->user_id = $user->id;
					$userprofile->social_type = $social_type;
					$userprofile->social_id = $social_id;
					$userprofile->country_id = $post_data['country_id'];
					$userprofile->state_id = $post_data['state_id'];
					$userprofile->city_id = $post_data['city_id'];
					$userprofile->device_id = $post_data['device_id'];
					$userprofile->device_agent = $post_data['device_agent'];
					$userprofile->save();

					$otp = '123456';
					return response()->json(['statusKey' => "0", "statusMessage" => "success", 'result' => ["otp" => $otp]], 201);
				}
      
			} else {
				return response()->json(['statusKey' => "1", "statusMessage" => "Failure", 'result' => $err_arr], 201);
			}
		}
		
		
		public function verifyOTP(Request $request) {
			$post_data 	= $request->all();
			$action 		= $post_data['action'];
			$otp				= $post_data['otp'];
			$username		= $post_data['username'];
			$password		= 'password';
			$req_time 	= $post_data['requesttime'];
			$email 			= $post_data['emailid'];
			$mobile 		= $post_data['mobilenumber'];
			$device_id 	= $post_data['deviceid'];
			
			if($otp == '123456') {
				$user = User::select('*')->where('username', $username)->first();
				$user_arr = array(
					"userid" 			=> $user->id,
					"name"				=> $username,
					"emailid"			=> $email,
					"mobilenumber"=> $mobile
				);
				return response()->json(['statusKey' => "0", "statusMessage" => "success", 'result' => $user_arr], 201);
			} else {
				return response()->json(['statusKey' => "1", "statusMessage" => "Failure", 'result' => ["message" => "OTP validation failed"]], 201);
			}
		}
  }