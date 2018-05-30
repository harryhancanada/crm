@extends('layouts.master')
@section('heading')
     <div class="panel panel-primary shadow  " >
         <div class="panel-heading"><h1>{{ __('新增顾问') }}</h1></div>
@stop

@section('content')
    {!! Form::open([
            'route' => 'users.store',
            'files'=>true,
            'enctype' => 'multipart/form-data'

            ]) !!}
    @include('users.form', ['submitButtonText' => __('新建顾问')])

    {!! Form::close() !!}


@stop

     </div>