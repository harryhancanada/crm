@extends('layouts.master')

@section('content')


    <div class="col-lg-12 currenttask">

        <table class="table table-hover">
            <div class="panel panel-primary shadow  " >
                <div class="panel-heading">
            <h3>{{ __('权限设置') }}</h3>
                </div></div>
            <thead>
            <thead>
            <tr>
                <th>{{ __('级别名称') }}</th>
                <th>{{ __('级别说明') }}</th>
                <th>{{ __('操作') }}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($roles as $role)
                <tr>
                    <td>{{$role->display_name}}</td>
                    <td>{{Str_limit($role->description, 50)}}</td>

                    <td>   {!! Form::open([
            'method' => 'DELETE',
            'route' => ['roles.destroy', $role->id]
        ]); !!}
                        @if($role->id !== 1)
                            {!! Form::submit(__('Delete'), ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure?")']); !!}
                        @endif
                        {!! Form::close(); !!}</td>
                    </td>
                </tr>
            @endforeach

            </tbody>

        </table>

        <a href="{{ route('roles.create')}}">
            <button class="btn btn-success">{{ __('新增权限级别') }}</button>
        </a>

    </div>

@stop