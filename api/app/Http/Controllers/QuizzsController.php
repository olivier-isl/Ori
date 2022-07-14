<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use Validator;

//Use Quizzs Model
use App\Models\Quizzs;
// use App\Models\Courses;

//Use Resources to convert into json
use App\Http\Resources\QuizzsResource as QuizzsResource;
// use App\Http\Controllers\Quizzs_coursesController as Quizzs_coursesController;

class QuizzsController extends Controller
{
    public function index(Request $request){
        $ordering = 'desc';
        $orderBy = "created_at";
        $req = Quizzs::select('*');
        $limit = -1;
        $offset = 0;
        forEach($request->query() as $key => $val) {
            if($key == "orderBy") {
                $orderBy = $val;
            }
            else if($key == "order") {
                $ordering = $val;
            }
            else if($key == 'limit') {
                if($val > -1) {
                    $limit = $val;
                }
            }
            else if($key == 'offset') {
                if($val > 0) {
                    $offset = $val;
                }
            }
            else if($key != 'type') {
                if(gettype($val) == 'array') {
                    forEach($val as $k => $v) {
                        if($k == 0) {
                            $req = $req->where($key, $v);
                        } else {
                            $req = $req->orWhere($key, $v);
                        }
                    }
                } else {
                    $req = $req->where($key, $val);
                }
            }
            else if($key == 'status') {
                $courses = $courses->where($key, $val);
            }
        }
        $req = $req->orderBy($orderBy, $ordering);
        
        if($request->query('limit') != null && $limit > -1) {
            $req = $req->limit($limit);
        }
        if($request->query('offset') != null && $offset >= 0) {
            $req = $req->offset($offset);
        }
        $req = $req;
        
        return [
            'requests' => $request->query(),
            'data' => QuizzsResource::collection($req->get()),
            "length"    => count(QuizzsResource::collection($req->get())),
            'response' => 200,
        ];
    }

    public function quizzByName($name){
        $quizz = Quizzs::select('*')->where('name', 'LIKE', '%'.$name.'%')->get();
        return QuizzsResource::collection($quizz);
    }

    public function quizzById($id) {
        $quizz = Quizzs::select('*')->where('id', '=', $id)->get();
        return QuizzsResource::collection($quizz);
    }

    public function quizzByNameOrId($response){
        if(is_numeric($response)) {
            return self::quizzById($response);
        }
        if(is_string($response)) {
            return self::quizzByName($response);
        }
    }

    public function filterBystatus($status) {
        $quizz = Quizzs::select('*')->where('status', 'like', $status)->get();
        return QuizzsResource::collection($quizz);
    }

    public function search($word) {
        $query = Quizzs::select('*');
        $quizzs = $query->where(function ($query) use ($word) {
            $query->orWhere('title', 'like', '%'.$word.'%');
            $query->orWhere('description', 'like', '%'.$word.'%');
            $query->orWhere('permalink', 'like', '%'.$word.'%');
        })->get();


        return QuizzsResource::collection($quizzs);
    }

    public function store(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input['data'], [
            'title' => 'required',
            'permalink' => 'required',
            'created_at' => 'required',
            'updated_at' => 'required',
            'status' => 'required',
        ]);
        if($validator->fails()) {
            return $validator->errors();
        }
        $quizz = Quizzs::create($input['data']);
        $response = ['success' => true,
        'message' => 'Course created successfully',
        'data' => $quizz];
        return response()->json($response);
    }

    public function quizzsByCourseId($courses_id)
    {
        $quizzs_courses = DB::table('quizzs_courses')->select('quizzs_id')->where('courses_id', $courses_id)->get();
        $db = [];
        foreach ($quizzs_courses as $val) {
            $quizz = Quizzs::select('*')->where('id', $val->quizzs_id)->orderby('order','ASC')->get();
            array_push($db, ...$quizz);
        }
        return $db;
    }

    public function update(Request $request, $id) {
        $lesson = Quizzs::find($id);
        $lesson->update($request->all());
        return $lesson;
    }
}
