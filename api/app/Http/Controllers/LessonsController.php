<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use Validator;

//Use Lessons Model
use App\Models\Lessons;
// use App\Models\Courses;

//Use Resources to convert into json
use App\Http\Resources\LessonsResource as LessonsResource;
// use App\Http\Controllers\CoursesController  as CoursesController;
use App\Http\Controllers\Lessons_coursesController as Lessons_coursesController;

class LessonsController extends Controller
{
    public function index(Request $request){
        $ordering = 'desc';
        $orderBy = "created_at";
        $req = Lessons::select('*');
        $limit = -1;
        $offset = 0;
        $args = array();
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
            'data' => LessonsResource::collection($req->get()),
            "length"    => count(LessonsResource::collection($req->get())),
            'response' => 200,
        ];
    }

    public function lessonByName($name){
        $lesson = Lessons::select('*')->where('name', 'LIKE', '%'.$name.'%')->get();
        return LessonsResource::collection($lesson);
    }

    public function lessonById($id) {
        $lesson = Lessons::select('*')->where('id', '=', $id)->get();
        return LessonsResource::collection($lesson);
    }

    public function lessonByNameOrId($response){
        if(is_numeric($response)) {
            return self::lessonById($response);
        }
        if(is_string($response)) {
            return self::lessonByName($response);
        }
    }

    public function filterBystatus($status) {
        $lesson = Lessons::select('*')->where('status', 'like', $status)->get();
        return LessonsResource::collection($lesson);
    }

    public function search($word) {
        $query = Lessons::select('*');
        $lessons = $query->where(function ($query) use ($word) {
            $query->orWhere('title', 'like', '%'.$word.'%');
            $query->orWhere('description', 'like', '%'.$word.'%');
            $query->orWhere('permalink', 'like', '%'.$word.'%');
        })->get();


        return LessonsResource::collection($lessons);
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
        $lesson = Lessons::create($input['data']);
        $response = ['success' => true,
        'message' => 'Course created successfully',
        'data' => $lesson];
        if(count($input['courses']) > 0) {
            $lessons_courses = Lessons_coursesController::storeByLessonId($request, $lesson['id']);
            $response['courses'] = $lessons_courses;
        }
        return response()->json($response);
    }

    public function update(Request $request, $id) {
        $lesson = Lessons::find($id);
        $lesson->update($request->all());
        return $lesson;
    }

    public function delete(Request $request, $id) {
        $lesson = Lessons::find($id);
        Lessons_coursesController::deleteByLessonId($request, $id);
        $lesson->delete();
        return response()->json([
            'success' => true,
            'message' => 'lesson deleted successfully',
            'data' => $lesson
        ]);
    }

    public function lessonsByCourseId($courses_id)
    {
        $lessons_courses = DB::table('lessons_courses')->select('lessons_id')->where('courses_id', $courses_id)->get();
        $db = [];
        foreach ($lessons_courses as $val) {
            $lesson = Lessons::select('*')->where('id', $val->lessons_id)->orderby('order','ASC')->get();
            array_push($db, ...$lesson);
        }
        return $db;
    }
}
