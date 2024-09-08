<?php
namespace App\Forms;

use Illuminate\Support\Facades\Validator;
class EventForm{

    protected static $rule = [
        'user_id' => ['required'],
        'title' => ['required', ],
        'event_date' => ['required'],
        'event_time' => [
            'nullable',
        ],
        'description' => ['nullable'],
        'location' => ['nullable'],
        'data_reminder' => [
            'nullable',
            'array'
        ],
        'data_member' => [
            'nullable',
            'array'
        ]
    ];

    public static function add(array $data){
        return Validator::make( $data, static::$rule);
    }

    public static function  update($data){
        return Validator::make($data, ['id'=> ['required'], ...static::$rule]);
    }

    public static function detail($data){
        return Validator::make($data, [
            'id' => 'required'
        ]);
    }

    public static function delete($data){
        return Validator::make($data, [
            'id' => 'required'
        ]);
    }

    public static function addBatch($data){
        return Validator::make($data, [
            'file' => [
                'required',
                'file'
            ]
        ]);
    }


}
