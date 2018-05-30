@extends('layouts.master')
    @section('content')
    @include('partials.userheader')
<div class="col-sm-8">
  <el-tabs active-name="tasks" style="width:100%">
    <el-tab-pane label="申请" name="tasks">
        <table class="table table-hover" id="tasks-table">
        <h3>{{ __('所负责的申请') }}</h3>
            <thead>
                    <th>{{ __('申请名称') }}</th>
                    <th>{{ __('学生姓名') }}</th>
                    <th>{{ __('创建时间') }}</th>
                    <th>{{ __('截止时间') }}</th>
                    <th>
                        <select name="status" id="status-task">
                        <option value="" disabled selected>{{ __('申请状态') }}</option>
                            <option value="open">进行中</option>
                            <option value="closed">完成</option>
                            <option value="pause">暂停</option>
                            <option value="abandoned">无效</option>
                            <option value="all">所有</option>
                        </select>
                    </th>
                </tr>
            </thead>
        </table>
    </el-tab-pane>
    <el-tab-pane label="咨询" name="leads">
      <table class="table table-hover">
        <table class="table table-hover" id="leads-table">
                <h3>{{ __('所负责的咨询') }}</h3>
                <thead>
                <tr>
                    <th>{{ __('咨询名称') }}</th>
                    <th>{{ __('学生姓名') }}</th>
                    <th>{{ __('创建时间') }}</th>
                    <th>{{ __('截止时间') }}</th>
                    <th>
                        <select name="status" id="status-lead">
                        <option value="" disabled selected>{{ __('咨询状态') }}</option>
                            <option value="open">咨询中</option>
                            <option value="closed">已完成</option>
                            <option value="not interested">无兴趣</option>
                            <option value="all">所有</option>
                        </select>
                    </th>
                </tr>
                </thead>
            </table>
    </el-tab-pane>
    <el-tab-pane label="学生" name="clients">
         <table class="table table-hover" id="clients-table">
                <h3>{{ __('所负责的学生') }}</h3>
                <thead>
                <tr>
                    <th>{{ __('学生姓名') }}</th>
                    <th>{{ __('所在学校') }}</th>
                    <th>{{ __('联系电话') }}</th>
                </tr>
                </thead>
            </table>
    </el-tab-pane>
  </el-tabs>
  </div>
  <div class="col-sm-4">
  <h4>{{ __('申请概览') }}</h4>
<doughnut :statistics="{{$task_statistics}}"></doughnut>
<h4>{{ __('咨询概览') }}</h4>
<doughnut :statistics="{{$lead_statistics}}"></doughnut>
  </div>

   @stop 
@push('scripts')
        <script>
        $('#pagination a').on('click', function (e) {
            e.preventDefault();
            var url = $('#search').attr('action') + '?page=' + page;
            $.post(url, $('#search').serialize(), function (data) {
                $('#posts').html(data);
            });
        });

            $(function () {
              var table = $('#tasks-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('users.taskdata', ['id' => $user->id]) !!}',
                    columns: [

                        {data: 'titlelink', name: 'title'},
                        {data: 'client_id', name: 'Client', orderable: false, searchable: false},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'deadline', name: 'deadline'},
                        {data: 'status', name: 'status', orderable: false},
                    ]
                });

                $('#status-task').change(function() {
                selected = $("#status-task option:selected").val();
                    if(selected == 'open') {
                        table.columns(4).search(1).draw();
                    } else if(selected == 'closed') {
                        table.columns(4).search(2).draw();
                    }
                    else if(selected == 'pause') {
                        table.columns(4).search(4).draw();
                    }
                    else if(selected == 'abandoned') {
                        table.columns(4).search(3).draw();
                    }

                    else {
                         table.columns(4).search( '' ).draw();
                    }
              });  

          });
            $(function () {
                $('#clients-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('users.clientdata', ['id' => $user->id]) !!}',
                    columns: [

                        {data: 'clientlink', name: 'name'},
                        {data: 'company_name', name: 'company_name'},
                        {data: 'primary_number', name: 'primary_number'},

                    ]
                });
            });

            $(function () {
              var table = $('#leads-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('users.leaddata', ['id' => $user->id]) !!}',
                    columns: [

                        {data: 'titlelink', name: 'title'},
                        {data: 'client_id', name: 'Client', orderable: false, searchable: false},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'contact_date', name: 'contact_date'},
                        {data: 'status', name: 'status', orderable: false},
                    ]
                });

              $('#status-lead').change(function() {
                selected = $("#status-lead option:selected").val();
                    if(selected == 'open') {
                        table.columns(4).search(1).draw();
                    } else if(selected == 'closed') {
                        table.columns(4).search(2).draw();
                    }
                    else if(selected == 'not interested') {
                        table.columns(4).search(3).draw();
                    }
                    else {
                         table.columns(4).search( '' ).draw();
                    }
              });  
          });
        </script>
@endpush


