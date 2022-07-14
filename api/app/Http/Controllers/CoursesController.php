<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use Validator;


//Use Courses Model
use App\Models\Courses;

//Use Resources to convert into json
use App\Http\Resources\CoursesResource as CoursesResource;
// use App\Http\Controllers\LessonsController as LessonsController;
use App\Http\Controllers\Lessons_coursesController as Lessons_coursesController;

class CoursesController extends Controller
{

    public function index(Request $request){
        $ordering = 'desc';
        $orderBy = "created_at";
        $courses = Courses::select('*');
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
                            $courses = $courses->where($key, $v);
                        } else {
                            $courses = $courses->orWhere($key, $v);
                        }
                    }
                } else {
                    $courses = $courses->where($key, $val);
                }
            }
            else if($key == 'status') {
                $courses = $courses->where($key, $val);
            }
        }
        $courses = $courses->orderBy($orderBy, $ordering);
        
        if($request->query('limit') != null && $limit > -1) {
            $courses = $courses->limit($limit);
        }
        if($request->query('offset') != null && $offset >= 0) {
            $courses = $courses->offset($offset);
        }
        $courses = $courses;
        
        return [
            'requests' => $request->query(),
            'data' => CoursesResource::collection($courses->get()),
            "length"    => count(CoursesResource::collection($courses->get())),
            'response' => 200,
        ];
    }

    private static function courseByPermalink($name){
        $course = Courses::select('*')->where('permalink', 'like', '%'.$name.'%')->get();
        return [
            'data' => CoursesResource::collection($course),
            "length"    => count(CoursesResource::collection($course)),
            'response' => 200,
        ];
    }

    private static function courseById($id){
        $course = Courses::select('*')->where('id', $id)->get();
        return [
            'data' => CoursesResource::collection($course),
            "length"    => count(CoursesResource::collection($course)),
            'response' => 200,
        ];
    }

    public function courseByPermalinkOrId($response){
        if(is_numeric($response)) {
            return self::courseById($response);
        }
        else if(is_string($response)) {
            return self::courseByPermalink($response);
        }
    }

    public function filterBystatus($status) {
        $course = Courses::select('*')->where('status', 'like', $status)->get();
        return CoursesResource::collection($course);
    }

    public function search($word) {
        $query = Courses::select('*');
        $course = $query->where(function ($query) use ($word) {
            $query->orWhere('title', 'like', '%'.$word.'%');
            $query->orWhere('description', 'like', '%'.$word.'%');
            $query->orWhere('permalink', 'like', '%'.$word.'%');
        })->get();


        return CoursesResource::collection($course);
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
        $course = Courses::create($input['data']);
        $response = ['success' => true,
        'message' => 'Course created successfully',
        'data' => $course];
        if(count($input['lessons']) > 0) {
            $lessons_courses = Lessons_coursesController::storeByCourseId($request, $course['id']);
            $response['lessons'] = $lessons_courses;
        }
        return response()->json($response);
    }

    public function update(Request $request, $id) {
        $course = Courses::find($id);
        $course->update($request->all());
        return $course;
    }

    public function delete(Request $request, $id) {
        $course = Courses::find($id);
        Lessons_coursesController::deleteByCourseId($request, $id);
        $course->delete();
        return response()->json([
            'success' => true,
            'message' => 'course deleted successfully',
            'data' => $course
        ]);
    }
}
