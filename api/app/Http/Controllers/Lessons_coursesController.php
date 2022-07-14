<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Use Options Model
use App\Models\Lessons_courses;
use Validator;

//Use Resources to convert into json
use App\Http\Resources\Lessons_coursesResource as Lessons_coursesResource;

class Lessons_coursesController extends Controller
{

    public function getByCourseId($id) {
        $course = Lessons_courses::select('*')->where('courses_id', "=", $id)->orderBy('order')->get();
        return Lessons_coursesResource::collection($course);
    }
    public function getByLessonId($id) {
        $lesson = Lessons_courses::select('*')->where('lessons_id', "=", $id)->get();
        return Lessons_coursesResource::collection($lesson);
    }

    public function storeByCourseId(Request $request, $id = null) {
        $input = $request->all();
        $course = [];
        if(!isset($input['lessons'])) {
            $data = [
                'courses_id' => (int) $id,
                'lessons_id' => $input['lessons_id'],
                'quizz_id' => $input['quizz_id'],
                'order' => $input['order'],
            ];
            $validator = Validator::make($data, [
                'courses_id' => 'required',
                'lessons_id' => 'nullable',
                'quizz_id' => 'nullable',
                'order' => 'required',
            ]);
            if($validator->fails()) {
                return $validator->errors();
            }
            $course[] = Lessons_courses::create($data);
        } else {
            foreach($input['lessons'] as $key => $val) {
                $data = [
                    'courses_id' => (int) $id,
                    'lessons_id' => $val['lessons_id'],
                    'quizz_id' => $val['quizz_id'],
                    'order' => $val['order'],
                ];
                $validator = Validator::make($data, [
                    'courses_id' => 'required',
                    'lessons_id' => 'nullable',
                    'quizz_id' => 'nullable',
                    'order' => 'required',
                ]);
                if($validator->fails()) {
                    return $validator->errors();
                }
                $course[$key] = Lessons_courses::create($data);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Course created successfully',
            'data' => $course
        ]);
    }

    public function updateByCourseId(Request $request, $id) {
        $course = Lessons_courses::where('courses_id', '=', $id)->where('lessons_id', '=', $request->request->all()['lessons_id']);
        $course->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'order modified successfully',
            'data' => $course
        ]);
    }

    public function deleteByCourseIdAndLessonId(Request $request, $id) {
        // return  $request->request->all()['lessons_id'] === null;
        $course = Lessons_courses::where('courses_id', '=', $id);
        if( $request->request->all()['lessons_id'] === null) {
            $course = $course->where('quizz_id', '=', $request->request->all()['quizz_id']);
        }
        if( $request->request->all()['quizz_id'] === null) {
            $course = $course->where('lessons_id', '=', $request->request->all()['lessons_id']);
        }
        $course->delete();
        return response()->json([
            'success' => true,
            'message' => 'order modified successfully',
            'data' => $course
        ]);
    }

    public function deleteByCourseId(Request $request, $id) {
        $course = Lessons_courses::where('courses_id', '=', $id);
        $course->delete();
        return response()->json([
            'success' => true,
            'message' => 'order modified successfully',
            'data' => $course
        ]);
    }

    public function deleteByLessonId(Request $request, $id) {
        $course = Lessons_courses::where('lessons_id', '=', $id);
        $course->delete();
        return response()->json([
            'success' => true,
            'message' => 'order modified successfully',
            'data' => $course
        ]);
    }

    public function deleteByQuizzId(Request $request, $id) {
        $course = Lessons_courses::where('quizz_id', '=', $id);
        $course->delete();
        return response()->json([
            'success' => true,
            'message' => 'order modified successfully',
            'data' => $course
        ]);
    }
}
