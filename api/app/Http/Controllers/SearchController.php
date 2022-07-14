<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use Validator;

//Use Quizzs Model
// use App\Models\Quizzs;
// use App\Models\Courses;

//Use Resources to convert into json

class SearchController extends Controller
{
    public function search(Request $request, $word){
		$req = DB::table($request->all()['type'])->select('*')->where(function ($query) use ($word) {
            $query->orWhere('title', 'like', '%'.$word.'%');
            $query->orWhere('description', 'like', '%'.$word.'%');
            $query->orWhere('permalink', 'like', '%'.$word.'%');
        })->get();
		return response()->json([
			"data" => $req,
			"success"	=> true
		]);
    }
}
