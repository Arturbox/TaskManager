@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <form method="get">
                    <div class="col-md-3">
                        <label>Created user</label>
                        <select name="user_id" >
                            <option value="0">--- All</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}" @if(array_key_exists('user_id',$whereIntegers) && $whereIntegers['user_id'] == $user->id) selected @endif>{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Assigned user</label>
                        <select name="user_assign_id" >
                            <option value="0">--- All</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}"  @if(array_key_exists('user_assign_id',$whereIntegers) && $whereIntegers['user_assign_id'] == $user->id) selected @endif>{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Title</label>
                        <input type="text" name="title" value="{{$whereLike}}">
                    </div>
                    <div class="col-md-3">
                        <label>Search</label>
                        <input type="submit" value="Search">
                    </div>
                </form>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('tasks.create') }}"> Create New Task</a>
            </div>
        </div>
    </div>
    <div id="form-messages" class="alert success" role="alert" style="display: none;"></div>
    <table class="table table-bordered">
        <tr>
            <th>Number</th>
            <th>Title</th>
            <th>Created User</th>
            <th>Assigned User</th>
            <th>Created Time</th>
            <th>Status</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($tasks as $task)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $task->title }}</td>
                <td>{{ $task->createdUser }}</td>
                <td>{{ $task->assignedUser }}</td>
                <td>{{ $task->created_at }}</td>
                <td>{{ $task->statusName }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('tasks.show',$task->id) }}">Show</a>
                    @can('hasCreatedOrAssigned', $task)
                        <a class="btn btn-primary" href="{{ route('tasks.edit',$task->id) }}">Edit</a>
                    @endcan
                    @can('hasCreated', $task)
                        <form class="delete-task">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $task->id }}">
                            <button class="btn btn-danger">Delete</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>
@endsection
