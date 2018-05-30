@extends('layouts.master')
@section('heading')

@stop

@section('content')
    <div class="panel panel-primary shadow  " >
        <div class="panel-heading">
            <h1>学生管理</h1>
        </div></div>
    <table class="table table-hover " id="clients-table">
        <thead>
        <tr>
            <th>{{ __('姓名') }}</th>
            <th>{{ __('所属顾问') }}</th>
            <th>{{ __('所属类型') }}</th>
            <th>{{ __('所在学校') }}</th>
            <th>{{ __('微信号码') }}</th>
            <th>{{ __('电子邮件') }}</th>
            <th>{{ __('联系电话') }}</th>
            <th>{{ __('操作选项') }}</th>
            <th></th>
        </tr>
        </thead>
    </table>

@stop

@push('scripts')
<script>
    $(function () {
        $('#clients-table').DataTable({
            processing: true,
            serverSide: true,

            ajax: '{!! route('clients.data') !!}',
            columns: [

                {data: 'namelink', name: 'name'},
                {data: 'user_id', name: 'user_id'},
                {data: 'industry', name: 'industry'},
                {data: 'company_name', name: 'company_name'},
                {data: 'vat', name: 'vat'},
                {data: 'email', name: 'email'},
                {data: 'primary_number', name: 'primary_number'},
                @if(Entrust::can('client-update'))   
                { data: 'edit', name: 'edit', orderable: false, searchable: false},
                @endif
                @if(Entrust::can('client-delete'))   
                { data: 'delete', name: 'delete', orderable: false, searchable: false},
                @endif

            ]
        });
    });
</script>
@endpush
