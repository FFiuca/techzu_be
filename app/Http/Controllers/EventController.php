<?php

namespace App\Http\Controllers;

use App\Sources\Services\EventReminderService;
use Illuminate\Http\Request;
use App\Forms\EventForm;
use App\Sources\Services\EventService;
use App\Models\EventReminder;
use App\Models\EventMember;
use App\Sources\Services\EventMemberService;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Helpers\MainHelper;

class EventController extends Controller
{
    protected $event, $eventMember, $eventReminder;
    function __construct(){
        $this->event = new EventService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        try{
            $data = $this->event->get($data);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'data' => MainHelper::messageError($e)
            ], 500);
        }

        return response()->json([
            'status'=> 200,
            'data' => $data
        ], 200);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $form=null;
        $data = $request->input('data');
        $member = $request->input('data_member');
        $reminder = $request->input('data_reminder');
        try{
            $form = EventForm::add([
                ...$data,
                'data_reminder' => $reminder,
                'data_member' => $member,
            ]);
            if($form->fails()==true)
                throw new ValidationException($form);

            // transaction db
            DB::beginTransaction();

            $data = $this->event->add($data);

            // sync member
            (new EventMemberService($data))->add($member);

            // sync reminder
            (new EventReminderService($data))->add($reminder);

            DB::commit();
        }catch(ValidationException $e){
            return response()->json([
                'status' => 400,
                'data' => $form->getMessageBag()
            ], 400);
        }catch(Exception $e){
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'data' => MainHelper::messageError($e)
            ], 500);
        }

        return response()->json([
            'status'=> 200,
            'data' => $data
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $form = EventForm::detail(['id'=> $id]);
            if($form->fails()==true)
                throw new ValidationException($form);

            $data = $this->event->detail($id);
        }catch(ValidationException $e){
            return response()->json([
                'status' => 400,
                'data' => $form->getMessageBag()
            ], 400);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'data' => MainHelper::messageError($e)
            ], 500);
        }

        return response()->json([
            'status'=> 200,
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $form=null;
        $data = $request->input('data');
        $member = $request->input('data_member');
        $reminder = $request->input('data_reminder');
        try{
            $form = EventForm::update([
                ...$data,
                'id' => $id,
                'data_reminder' => $reminder,
                'data_member' => $member,
            ]);
            if($form->fails()==true)
                throw new ValidationException($form);

            DB::beginTransaction();

            $data = $this->event->update($id, $data);

            // sync member
            (new EventMemberService($data))->add($member);

            // sync reminder
            (new EventReminderService($data))->add($reminder);

            DB::commit();
        }catch(ValidationException $e){
            return response()->json([
                'status' => 400,
                'data' => $form->getMessageBag()
            ], 400);
        }catch(Exception $e){
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'data' => MainHelper::messageError($e)
            ], 500);
        }

        return response()->json([
            'status'=> 200,
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $form = null;
        $data = null;
        try{
            $form = EventForm::delete(['id'=> $id]);
            if($form->fails()==true)
                throw new ValidationException($form);

            DB::beginTransaction();

            $data = $this->event->delete($id);

            DB::commit();
        }catch(ValidationException $e){
            return response()->json([
                'status' => 400,
                'data' => $form->getMessageBag()
            ], 400);
        }catch(Exception $e){
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'data' => MainHelper::messageError($e)
            ], 500);
        }

        return response()->json([
            'status'=> 200,
            'data' => $data
        ], 200);
    }

    public function storeBatch(Request $request){
        $form = null;
        $data = null;
        try{
            $file = $request->file('file');
            $form = EventForm::addBatch(['file'=> $file]);
            if($form->fails()==true)
                throw new ValidationException($form);

            DB::beginTransaction();

            $data = $this->event->readFromExcel($file);
            $data = $this->event->addBatch($data, 1); // user id make static due auth not config yet

            DB::commit();
        }catch(ValidationException $e){
            return response()->json([
                'status' => 400,
                'data' => $form->getMessageBag()
            ], 400);
        }catch(Exception $e){
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'data' => MainHelper::messageError($e)
            ], 500);
        }

        return response()->json([
            'status'=> 200,
            'data' => $data
        ], 200);
    }
}
