@extends('layouts.master')
@section('heading')
        <div class="panel panel-primary shadow  " >
            <div class="panel-heading"><h1>新建申请</h1></div>
@stop

@section('content')

    {!! Form::open([
            'route' => 'tasks.store'
            ]) !!}

    <div class="form-group">
        {!! Form::label('title', __('名称') , ['class' => 'control-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('description', __('详细说明'), ['class' => 'control-label']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-inline">
        <div class="form-group col-sm-6 removeleft ">
            {!! Form::label('deadline', __('截止时间'), ['class' => 'control-label']) !!}
            {!! Form::date('deadline', \Carbon\Carbon::now()->addDays(3), ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6 removeleft removeright">
            {!! Form::label('status', __('状态'), ['class' => 'control-label']) !!}
            {!! Form::select('status', array(
            '1' => '进行中', '2' => '已完成','3' => '无效','4' => '暂停'), null, ['class' => 'form-control'] )
         !!}
        </div>

    </div>
    <div class="form-group form-inline">
        {!! Form::label('user_assigned_id', __('分配顾问'), ['class' => 'control-label']) !!}
        {!! Form::select('user_assigned_id', $users, null, ['class' => 'form-control']) !!}
        @if(Request::get('client') != "")
            {!! Form::hidden('client_id', Request::get('client')) !!}
        @else
            {!! Form::label('client_id', __('所属学生'), ['class' => 'control-label']) !!}
            {!! Form::select('client_id', $clients, null, ['class' => 'form-control']) !!}
        @endif
    </div>

    {!! Form::submit(__('新建申请'), ['class' => 'btn btn-primary']) !!}

    {!! Form::close() !!}





@stop

        </div>