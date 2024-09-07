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

class EventController extends Controller
{
    protected $event, $eventMember, $eventReminder;
    function __construct(){
        $this->event = new EventService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            $form = EventForm::add($data);
            if($form->valid()==false)
                throw new Exception('error');

            $data = $this->event->add($data);

            // sync member
            (new EventMemberService($data))->add($member);

            // sync reminder
            (new EventReminderService($data))->add($reminder);
        }catch(ValidationException $e){
            return response()->json([
                'status' => 400,
                'data' => $form->getMessageBag()
            ], 400);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'data' => $e->getMessage()
            ], 500);
        }

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $form = EventForm::detail(['id'=> $id]);
            if($form->valid()==false)
                throw new Exception('error');

            $data = $this->event->detail($id);
        }catch(ValidationException $e){
            return response()->json([
                'status' => 400,
                'data' => $form->getMessageBag()
            ], 400);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'data' => $e->getMessage()
            ], 500);
        }

        return response()->json($data, 200);
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
                'id' => $id
            ]);
            if($form->valid()==false)
                throw new Exception('error');

            $data = $this->event->update($id, $data);

            // sync member
            (new EventMemberService($data))->add($member);

            // sync reminder
            (new EventReminderService($data))->add($reminder);
        }catch(ValidationException $e){
            return response()->json([
                'status' => 400,
                'data' => $form->getMessageBag()
            ], 400);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'data' => $e->getMessage()
            ], 500);
        }

        return response()->json($data, 200);
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
            if($form->valid()==false)
                throw new Exception('error');

            $data = $this->event->delete($id);
        }catch(ValidationException $e){
            return response()->json([
                'status' => 400,
                'data' => $form->getMessageBag()
            ], 400);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'data' => $e->getMessage()
            ], 500);
        }

        return response()->json($data, 200);
    }
}
