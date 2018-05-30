@extends('layouts.master')

@section('heading')

@stop

@section('content')
@push('scripts')
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush

    <div class="row">
        @include('partials.clientheader')
        @include('partials.userheader')
    </div>

    <div class="row">
        <div class="col-md-9">
            @include('partials.comments', ['subject' => $tasks])
        </div>
        <div class="col-md-3">
            <div class="panel panel-primary shadow">
                <div class="panel-heading">

                <p>{{ __('申请管理') }}</p>
            </div>
                <div class="panel-body">
            <div class="sidebarbox">
                <p>{{ __('所属顾问') }}:
                    <a href="{{route('users.show', $tasks->user->id)}}">
                        {{$tasks->user->name}}</a></p>
                <p>{{ __('创建时间') }}: {{ date('d F, Y, H:i', strtotime($tasks->created_at))}} </p>

                @if($tasks->days_until_deadline)
                    <p>{{ __('截止时间') }}: <span style="color:red;">{{date('d, F Y', strTotime($tasks->deadline))}}  还有

                            @if($tasks->status == 1)({!! $tasks->days_until_deadline !!})@endif 天</span></p>
                    <!--Remove days left if tasks is completed-->

                @else
                    <p>{{ __('截止时间') }}: <span style="color:green;">{{date('d, F Y', strTotime($tasks->deadline))}}  还有

                            @if($tasks->status == 1)({!! $tasks->days_until_deadline !!})@endif 天</span></p>
                    <!--Remove days left if tasks is completed-->
                @endif

                @if($tasks->status == 1)
                    {{ __('申请状态') }}: <span class="label label-primary">{{ __('进行中') }}</span>
                @endif

                @if($tasks->status == 2)
                    {{ __('申请状态') }}: <span class="label label-success">{{ __('已完成') }}</span>
                @endif

                @if($tasks->status == 3)
                    {{ __('申请状态') }}: <span class="label label-danger">{{ __('无效') }}</span>
                @endif

                @if($tasks->status == 4)
                    {{ __('申请状态') }}: <span class="label label-warning">{{ __('暂停') }}</span>
                @endif


            </div>
                    <br>


                {!! Form::model($tasks, [
               'method' => 'PATCH',
                'url' => ['tasks/updateassign', $tasks->id],
                ]) !!}
                {!! Form::select('user_assigned_id', $users, null, ['class' => 'form-control ui search selection top right pointing search-select', 'id' => 'search-select']) !!}
                {!! Form::submit(__('更改所属顾问'), ['class' => 'btn btn-primary form-control closebtn']) !!}
                {!! Form::close() !!}
                <hr>
                {!! Form::model($tasks, [
          'method' => 'PATCH',
          'url' => ['tasks/updatestatus', $tasks->id],
          ]) !!}
                {!! Form::select('status', array(
            '1' => '进行中', '2' => '已完成','3' => '无效','4' => '暂停'), null, ['class' => 'form-control'] )
         !!}
                {!! Form::submit(__('更改申请状态'), ['class' => 'btn btn-success form-control closebtn']) !!}
                {!! Form::close() !!}


</div></div>
    <div class="panel panel-primary shadow">
                        <div class="panel-heading">
                <p>{{ __('进度管理') }}</p>
            </div>
        <div class="panel-body">
            <table class="table table_wrapper ">
                <tr>
                    <th>{{ __('步骤') }}</th>
                    <th>{{ __('收费记录') }}</th>
                </tr>
                <tbody>
               @foreach($invoice_lines as $invoice_line)
                    <tr>
                        <td style="padding: 5px">{{$invoice_line->title}}</td>
                        <td style="padding: 5px">$ {{$invoice_line->price}} </td>
                    </tr>
                @endforeach
     
                </tbody>
            </table>
            <br/>
            <button type="button" {{ $tasks->canUpdateInvoice() == 'true' ? '' : 'disabled'}} class="btn btn-primary form-control" value="add_time_modal" data-toggle="modal" data-target="#ModalTimer" >
                {{ __('新增申请进度') }}
            </button>
            @if($tasks->invoice)
                <a href="../invoices/{{$tasks->invoice->id}}">查看收费记录</a>
            @endif
        </div></div>
            <div class="panel panel-primary shadow">
                <div class="panel-heading">
                    <p>{{ __('历史记录') }}</p>
                </div>
                <div class="panel-body">
            <div class="activity-feed movedown">
                @foreach($tasks->activity as $activity)
                    <div class="feed-item">
                        <div class="activity-date">{{date('d, F Y H:i', strTotime($activity->created_at))}}</div>
                        <div class="activity-text">{{$activity->text}}</div>

                    </div>
                @endforeach
            </div>

            @include('invoices._invoiceLineModal', ['title' => $tasks->title, 'id' => $tasks->id, 'type' => 'task'])
        </div>
    </div>
        </div>
    </div>
@stop