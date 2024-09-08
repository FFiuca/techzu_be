<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class ArrayHelper {

    static function explodeThenFilter($data, $delimeter=',', $callback = null){
        if(is_null($callback))
            $callback = fn($e)=> Str::of($e)->isEmpty()==false;

        $data = collect(explode($delimeter, $data));
        $data = $data->map(fn($e)=> trim($e))
                        ->filter(fn($e) => $callback($e))
                        ->values()
                        ->toArray();

        return $data;
    }
}

