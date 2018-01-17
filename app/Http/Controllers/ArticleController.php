<?php

namespace App\Http\Controllers;

use Hash;
use Storage;
use App\User;
use App\UserProfile;
use App\Article;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ArticleController extends BaseController {
	public function ws_articles(Request $request){
		$post_data 	= $request->json()->all();
		$sortBy = (isset($post_data['sortBy']) && !empty($post_data['sortBy']))?$post_data['sortBy']:false; 
		$articlecount = Article::get()->count();
		$offset = (isset($post_data['offset']) && !empty($post_data['offset']))?$post_data['offset']:0; 
		$limit = (isset($post_data['limit']) && !empty($post_data['limit']))?$post_data['limit']:10; 
		$articles = Article::when($sortBy, function ($query) use ($sortBy) {
                    return $query->orderBy($sortBy);
                }, function ($query) {
                    return $query->orderBy('created_at');
                })->offset($offset)->limit($limit);
		$articles = $articles->get()->toArray();

		$articlesArr = array();
		foreach ($articles as $key => $value) {
			$articlesArr[$key] = $value;
			$file_path = \Storage::url($value['image']);
			$url = asset($file_path);
			$image_data = file_get_contents($url);
			$image = base64_encode($image_data);
			$articlesArr[$key]['image'] = $image;
		}

		return response()->json(['statusKey' => "0", "statusMessage" => "success", 'result' => $articlesArr,'count'=> $articlecount], 201);
	}
	
}