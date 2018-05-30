@extends('layouts.master')
@section('heading')
        <div class="panel panel-primary shadow  " >
            <div class="panel-heading"> <h1>编辑学生信息({{$client->name}})</h1></div></div>
@stop

@section('content')
    {!! Form::model($client, [
            'method' => 'PATCH',
            'route' => ['clients.update', $client->id],
            ]) !!}
    @include('clients.form', ['submitButtonText' => __('更新')])

    {!! Form::close() !!}

@stop