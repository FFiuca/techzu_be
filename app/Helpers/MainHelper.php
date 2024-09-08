<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class MainHelper {

    public static function messageError($e)
    {
        // return $e->getMessage() . ', line ' . $e->getLine() . ', class ' . get_class($e);
        $prev = $e->getPrevious();
        // dump($prev);
        return sprintf(
            '%s, line %s, class %s, file %s. Previous: %s. Trace %s',
            $e->getMessage(),
            $e->getLine(),
            get_class($e),
            $e->getFile(),
            is_object($prev) ? json_encode((array) $prev) : strval($prev),
            $e->getTraceAsString(),
        );
    }
}

