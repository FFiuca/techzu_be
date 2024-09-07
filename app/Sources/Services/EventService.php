<?php
namespace App\Sources\Services;
use App\Sources\Repositories\EventRepository;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EventImport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// will be base class, if further have other changes will be create new class which extends to this class
class EventService extends EventRepository{
    static $prefixId = 'ev-';

    function add(array $data): Model|array|bool{
        $add = Event::create($data);

        return $add;
    }

    function update(int|string $id, array $data): Model|array|bool{
        $update = Event::where('id', $id)->update($data);

        return Event::find($id);
    }

    function delete(int|string $id): bool{
        $delete = Event::where('id', $id)->delete();

        return $delete;
    }

    function detail(int|string $id): array{
        $data = Event::where('id', $id)->first();
        return $data;
    }

    function addBatch($data){
        $data = collect($data)->chunk(100);
        foreach($data as $key=>$chunk){
            $insert = Event::insert($chunk);
            if ($insert==false)
                throw new \Exception('Add events in batch error');
        }

        return true;
    }

    function readEventFromExcel(Request $file){
        $data = Excel::toArray(new EventImport, $file);

        // to prevent out of memory
        $yield = function($d){
            for ($i=0; $i<count($d); $i++)
                yield $d[$i];
        };

        foreach($yield($data) as $key=>$r){
            $data[$key]['id'] = static::generateId();
        }

        return $data;
    }

    static function generateId(){
        $generator = time().'-'.Str::random(4);
        return static::$prefixId. md5($generator);
    }

}

?>
