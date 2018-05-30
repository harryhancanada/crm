@extends('layouts.master')

@section('content')
    <div class="col-lg-12 currenttask">
        <table class="table table-hover">
            <div class="panel panel-primary shadow  " >
                <div class="panel-heading">
            <h3>部门管理</h3>
                </div></div>
            <thead>
            <thead>
            <tr>
                <th>{{ __('名称') }}</th>
                <th>{{ __('描述') }}</th>
                @if(Entrust::hasRole('administrator'))
                    <th>{{ __('操作') }}</th>
                @endif
            </tr>
            </thead>
            <tbody>

            @foreach($department as $dep)
                <tr>
                    <td>{{$dep->name}}</td>
                    <td>{{Str_limit($dep->description, 50)}}</td>
                    @if(Entrust::hasRole('administrator'))
                        <td>   {!! Form::open([
            'method' => 'DELETE',
            'route' => ['departments.destroy', $dep->id]
        ]); !!}
                            {!! Form::submit( __('Delete'), ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Are you sure?")']); !!}

                            {!! Form::close(); !!}</td></td>
                    @endif
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>

@stop