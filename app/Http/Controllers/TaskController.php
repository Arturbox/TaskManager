<?php

namespace App\Http\Controllers;

use Gate;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $whereIntegers = [];
        $whereLike = $request->get('title');
        $request->request->remove('title');
        foreach ($request->all() as $field=>$value){
            if ((int)$value>0)
                $whereIntegers[$field] = $value;
        }

        $tasks = Task::query()
            ->when(count($whereIntegers),function ($query) use ($whereIntegers){
                return $query->where($whereIntegers);
            })
            ->when($whereLike,function ($query,$whereLike){
                return $query->where('title','LIKE','%'.$whereLike.'%');
            })
            ->get();


        $users = User::all();
        return view('tasks.index',compact('tasks','users','whereIntegers','whereLike'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::query()->get();
        return view('tasks.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'title' => 'required',
                'user_assign_id' => 'required'
            ]);
            //Add auth user id
            $request->merge([
                'user_id' => Auth::user()->getKey()
            ]);
            Task::create($request->all());

            return response()->json([
                'data' => [
                    'status'  => 200,
                    'message' => 'Task updated successfully',
                ],
            ], 200);

        }
        catch (Exception $e) {
            return response()->json([
                'data' => [
                    'status'  => 500,
                    'message' => 'Error',
                ],
            ], 500);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('tasks.show',compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        if (!Auth::user()->can('hasCreatedOrAssigned',$task))
            abort(403);

        $users = User::query()->get();
        return view('tasks.edit',compact('task','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        if (!Auth::user()->can('hasCreatedOrAssigned',$task))
            throw new Exception('permission denied');

        try{
            if (Auth::user()->can('hasCreated', $task))
                $request->validate([
                    'title' => 'required',
                    'user_assign_id' => 'required',
                    'status' => 'required',
                ]);
            elseif (Auth::user()->can('hasAssigned', $task))
                $request->validate([
                    'status' => 'required',
                ]);

            $task->update($request->all());

            return response()->json([
                'data' => [
                    'status'  => 200,
                    'message' => 'Task updated successfully',
                ],
            ], 200);

        }
        catch (Exception $e) {
            return response()->json([
                'data' => [
                    'status'  => 500,
                    'message' => 'Error',
                ],
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try{
            if (! (Auth::user()->can('hasCreated', $task)))
                throw new Exception('permission denid');

            $task->delete();

            return response()->json([
                'data' => [
                    'status'  => 200,
                    'message' => 'Task Deleted successfully',
                ],
            ], 200);

        }
        catch (Exception $e) {
            return response()->json([
                'data' => [
                    'status'  => 500,
                    'message' => 'Error',
                ],
            ], 500);
        }
    }


    public function averageTasks(){
        $users = User::all();
        foreach ($users as $user) {
            echo $user->averageTasks.'<br>';
        }

        dd(111111111);
    }
}
