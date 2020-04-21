@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Task</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('tasks.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div id="form-messages" class="alert success" role="alert" style="display: none;"></div>

    <form id="SaveData">
        @method('PUT')
        <input type="hidden" id="id" name="id" value="{{$task->id}}">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Title:</strong>
                    <input type="text" name="title" value="{{ $task->title }}" class="form-control" placeholder="Title">
                </div>
            </div>
            @can('hasCreated', $task)
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Assign user:</strong>
                    <select name="user_assign_id" class="form-control">
                        @foreach($users as $user)
                            <option value="{{$user->id}}"  @if($task->user_assign_id == $user->id) selected @endif>{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endcan
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Status:</strong>
                    <select name="status" class="form-control">
                        @foreach($task->statuses() as $key => $status)
                            <option value="{{$key}}"  @if($task->status == $key) selected @endif  >{{$status}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection
