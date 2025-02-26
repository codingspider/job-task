<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends BaseController
{
    /**
     * Store a newly created task in db.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        DB::beginTransaction();
        try {
            $input = $request->all();
            $task = Task::create($input);
            $success['task'] =  $task;
            DB::commit();
            return $this->sendResponse($success, 'Task created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Server error.', ['error'=>$e->getMessage()]);
        }
    }
    
    
    /**
     * Update a task in db.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        DB::beginTransaction();
        try {
            $input = $request->except('_method');
            $task = Task::where('id', $id)->update($input);
            $success['task'] =  $task;
            DB::commit();
            return $this->sendResponse($success, 'Task update successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Server error.', ['error'=>$e->getMessage()]);
        }
    }

    /**
     * Display a listing of the pending tasks.
     */
    public function pendingTask()
    {
        try {
            $tasks = Task::whereIsCompleted(false)->get();
            $success['tasks'] =  $tasks;
            return $this->sendResponse($success, 'Post retrived successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Server error.', ['error'=>$e->getMessage()]);
        }
    }

}
