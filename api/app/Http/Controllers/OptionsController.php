<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Use Options Model
use App\Models\Options;
use Validator;

//Use Resources to convert into json
use App\Http\Resources\OptionsResource as OptionsResource;

class OptionsController extends Controller
{

    public function index()
    {
        $options = Options::get();
        return OptionsResource::collection($options);
    }

    public function get() {
        $newOptions['data'] = [];
        $options = Options::get(['name','value'])->toArray();
        foreach($options as $key => $val) {
            $newOptions['data'] = (object) array_merge((array) $newOptions['data'], array($options[$key]['name'] => $options[$key]['value']));
        }
        return $newOptions;
    }

    public function update(Request $request) {
        foreach($request->request as $key => $val) {
            $option = Options::where('name', 'LIKE', (String)$key);
            $option->update(['value' => (String)$val]);
        }
        $options = Options::get();
        return OptionsResource::collection($options);
        // $option = Options::where('name', '=', $name);
        // $option->update($request->all());
        // return $option;
    }
}
