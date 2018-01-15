<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\User;
use App\UserProfile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ProfileController extends BaseController {

	public function updateProfile(Request $request){
		$post_data 	= $request->json()->all();
		//echo "<pre>"; print_r($post_data); exit;
		$userprofile = UserProfile::where('user_id',$post_data['user_id'])->first();
		$user = User::find($post_data['user_id']);

		$user->name     = (isset($post_data['name']) && !empty($post_data['name']))?$post_data['name']:$user->name;
		$user->email    = (isset($post_data['emailid']) && !empty($post_data['emailid']))?$post_data['emailid']:$user->email;
		$user->avatar   = 'users/default.png';
		$user->mobile 	= (isset($post_data['mobilenumber']) && !empty($post_data['mobilenumber']))?$post_data['mobilenumber']:$user->mobile;
		if(isset($post_data['password']) && !empty($post_data['password'])){
			$user->password = Hash::make($post_data['password']);
		}

		$user->save();

		$userprofile->country_id = (isset($post_data['country_id']) && !empty($post_data['country_id']))?$post_data['country_id']:$userprofile->country_id;
		$userprofile->state_id = (isset($post_data['state_id']) && !empty($post_data['state_id']))?$post_data['state_id']:$userprofile->state_id;
		$userprofile->city_id = (isset($post_data['city_id']) && !empty($post_data['city_id']))?$post_data['city_id']:$userprofile->city_id;
		$userprofile->vehicle_brand = (isset($post_data['vehicle_brand']) && !empty($post_data['vehicle_brand']))?$post_data['vehicle_brand']:$userprofile->vehicle_brand;
		$userprofile->vehicle_model = (isset($post_data['vehicle_model']) && !empty($post_data['vehicle_model']))?$post_data['vehicle_model']:$userprofile->vehicle_model;
		$userprofile->vehicle_reg_no = (isset($post_data['vehicle_reg_no']) && !empty($post_data['vehicle_reg_no']))?$post_data['vehicle_reg_no']:$userprofile->vehicle_reg_no;
		$userprofile->save();

		return response()->json(['statusKey' => "0", "statusMessage" => "success", 'result' => []], 201);
	}

}