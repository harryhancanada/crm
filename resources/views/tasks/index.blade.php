@extends('layouts.master')
@section('heading')
    <div class="panel panel-primary shadow  " >
        <div class="panel-heading">
            <h1>申请管理</h1>
        </div>
@stop

@section('content')
            <div class="panel-body">
    <table class="table table-hover" id="tasks-table">
        <thead>
        <tr>

            <th>{{ __('申请名称') }}</th>
            <th>{{ __('建立时间') }}</th>
            <th>{{ __('截止时间') }}</th>
            <th>{{ __('所属顾问') }}</th>
            <th>{{ __('申请状态') }}</th>
            <th>{{ __('操作选项') }}</th>
            <th>{{ __('') }}</th>
        </tr>
        </thead>
    </table>
            </div>
    </div>
@stop

@push('scripts')
<script>
    $(function () {
        $('#tasks-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('tasks.data') !!}',
            columns: [

                {data: 'titlelink', name: 'title'},
                {data: 'created_at', name: 'created_at'},
                {data: 'deadline', name: 'deadline'},
                {data: 'user_assigned_id', name: 'user_assigned_id',},
                {data: 'status', name: 'status',},
                    
                {
                    data: 'edit', name: 'edit', orderable: false, searchable: false
                },
                   
                    @if(Entrust::can('task-delete'))
                {
                    data: 'delete', name: 'delete', orderable: false, searchable: false
                },
                @endif

            ]
        });

    });
    




</script>
@endpush
