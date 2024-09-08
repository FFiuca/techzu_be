<?php
namespace App\Sources\Services;
use App\Sources\Repositories\EventRepository;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EventImport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Helpers\ArrayHelper;
use App\Models\EventMember;
use App\Models\EventReminder;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

// will be base class, if further have other changes will be create new class which extends to this class
class EventService extends EventRepository{
    static $prefixId = 'ev-';

    function add(array $data): Model|array|bool{
        $add = Event::create($data);

        return $add;
    }

    function update(int|string $id, array $data): Model|array|bool{
        Event::where('id', $id)->update($data);

        return Event::find($id);
    }

    function delete(int|string $id): bool{
        $delete = Event::where('id', $id)->delete();

        return $delete;
    }

    function detail(int|string $id): Model|array{
        $data = Event::where('id', $id)->with([
            'eventMemberRegistered' => fn($que) => $que->with('user'),
            'eventMemberExternal',
            'eventReminder'
        ])->first();

        return $data;
    }

    function addBatch(array $data, $userId): bool{
        // $data = $data;

        //NOTE - because limitation time and complex will use basic add event succerfice the performance
        foreach($data as $key=>$r){
            $temp = collect($r)->only([
                'id',
                'title',
                'event_date',
                'event_time',
                'description',
            ])->toArray();

            Event::forceCreateQuietly([
                ...$temp,
                'user_id'=> $userId,
            ]); // to prevent observer override

            $insert = EventReminder::insert($r['data_reminder']);
            if ($insert==false)
                throw new Exception('Insert event reminder add batch error');
            $insert = EventMember::insert($r['data_member']);
            if ($insert==false)
                throw new Exception('Insert event member add batch error');
        }

        return true;
    }

    function normalizeDataExcel($data): array{
        $result = collect($data)->only([
            'title',
            'date',
            'time',
            'description',
            'member',
            'reminder'
        ])->toArray();

        $result['event_date'] = Date::excelToDateTimeObject($result['date'])->format('Y-m-d');
        $result['event_time'] = Date::excelToDateTimeObject($result['time'])->format('H:i:00');

        $result['data_member'] = ArrayHelper::explodeThenFilter($result['member']);
        $result['data_reminder'] = ArrayHelper::explodeThenFilter($result['reminder'], ';');

        foreach($result['data_member'] as $key=>$r){
            $result['data_member'][$key] = [
                'status_member' => 'external', // treated as external member
                'email_external_member' => $r
            ];
        }

        foreach($result['data_reminder'] as $key=>$r){
            $result['data_reminder'][$key] = [
                'time_before' => $r
            ];
        }

        unset($result['member']);
        unset($result['reminder']);

        return $result;
    }

    function readFromExcel(UploadedFile $file) :array {
        $data = Excel::toArray(new EventImport, $file)[0];
        $data = collect($data)->filter(fn($e)=> isset($e['title']) and isset($e['date']))
                    ->values()
                    ->toArray();

        // to prevent out of memory
        $yield = function($d){
            for ($i=0; $i<count($d); $i++)
                yield $d[$i];
        };

        foreach($yield($data) as $key=>$r){
            $data[$key] = [
                ...$this->normalizeDataExcel($r),
                'id' => static::generateId(),
            ];

            $data[$key]['data_reminder'] = collect($data[$key]['data_reminder'])->map(fn($e)=> [
                ...$e,
                'event_id' => $data[$key]['id']
            ])->toArray();

            $data[$key]['data_member'] = collect($data[$key]['data_member'])->map(fn($e)=> [
                ...$e,
                'event_id' => $data[$key]['id']
            ])->toArray();
        }

        // dump($data);
        return $data;
    }

    public function get(array $data): array|Collection{
        $q = new Event;
        // dump($data);
        if(isset($data['date_start']))
            $q = $q->where('event_date', '>=', $data['date_start']);
        if(isset($data['date_end']))
            $q = $q->where('event_date', '<=', $data['date_end']);

        if(isset($data['page']) and $data['page']>0){
            $page = $data['page'];
            $limit = $data['limit']?? 100;
            $offset = ($page-1)*$limit;

            $q = $q->limit($limit)->offset($offset);
        }

        $q = $q->orderBy('created_at', 'asc');
        // dump($q->to);
        return $q->get();
    }

    static function generateId(){
        $generator = time().'-'.Str::random(4);
        return static::$prefixId. md5($generator);
    }

}

?>
