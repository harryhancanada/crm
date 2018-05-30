@extends('layouts.master')
@section('heading')
    <div class="panel panel-primary shadow  " >
        <div class="panel-heading">
    <h1>{{__('咨询管理')}}</h1>
        </div></div>
@stop

@section('content')
    <table class="table table-hover" id="leads-table">
        <thead>
        <tr>

            <th>{{ __('咨询名称') }}</th>
            <th>{{ __('建立者') }}</th>
            <th>{{ __('截止日期') }}</th>
            <th>{{ __('所属顾问') }}</th>
            <th>{{ __('咨询状态') }}</th>
            <th>{{ __('操作选项') }}</th>
            <th>{{ __('') }}</th>
        </tr>
        </thead>
    </table>
@stop

@push('scripts')
<script>
    $(function () {
        $('#leads-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('leads.data') !!}',
            columns: [

                {data: 'titlelink', name: 'title'},
                {data: 'user_created_id', name: 'user_created_id'},
                {data: 'contact_date', name: 'contact_date',},
                {data: 'user_assigned_id', name: 'user_assigned_id'},
                {data: 'status', name: 'status'},
                    @if(Entrust::can('lead-update'))
                {
                    data: 'edit', name: 'edit', orderable: false, searchable: false
                },
                    @endif
                    @if(Entrust::can('lead-delete'))
                {
                    data: 'delete', name: 'delete', orderable: false, searchable: false
                },
                @endif

            ]
        });
    });
</script>
@endpush