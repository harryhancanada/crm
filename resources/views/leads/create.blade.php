@extends('layouts.master')
@section('heading')
           <div class="panel panel-primary shadow  " >
               <div class="panel-heading"><h1>{{ __('新建咨询') }}</h1></div>
@stop

@section('content')

    {!! Form::open([
            'route' => 'leads.store'
            ]) !!}

    <div class="form-group">
        {!! Form::label('title', __('咨询名称'), ['class' => 'control-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('description', __('详细说明'), ['class' => 'control-label']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-inline">
        <div class="form-group col-lg-3 removeleft">
            {!! Form::label('status', __('状态'), ['class' => 'control-label']) !!}
            {!! Form::select('status', array(
            '1' => '咨询中', '2' => '已完成', '3' => '客户无兴趣'), null, ['class' => 'form-control'] )
         !!}
        </div>
        <div class="form-group col-lg-4 removeleft">
            {!! Form::label('contact_date', __('下一次跟进日期'), ['class' => 'control-label']) !!}
            {!! Form::date('contact_date', \Carbon\Carbon::now()->addDays(7), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-lg-5 removeleft removeright">
            {!! Form::label('contact_time', __('时间'), ['class' => 'control-label']) !!}
            {!! Form::time('contact_time', '11:00', ['class' => 'form-control']) !!}
        </div>

    </div>


    <div class="form-group">
        {!! Form::label('user_assigned_id', __('分配顾问'), ['class' => 'control-label']) !!}
        {!! Form::select('user_assigned_id', $users, null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        @if(Request::get('client') != "")
            {!! Form::hidden('client_id', Request::get('client')) !!}
        @else
            {!! Form::label('client_id', __('所属学生'), ['class' => 'control-label']) !!}
            {!! Form::select('client_id', $clients, null, ['class' => 'form-control']) !!}
        @endif
    </div>

    {!! Form::submit(__('新建咨询'), ['class' => 'btn btn-primary']) !!}

    {!! Form::close() !!}


@stop

           </div>