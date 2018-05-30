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
            @include('partials.comments', ['subject' => $lead])
        </div>
        <div class="col-md-3">
            <div class="panel panel-primary shadow">
                <div class="panel-heading">
                <p> {{ __('咨询记录') }}</p>
                </div>
                <div class="panel-body">
            <div class="sidebarbox">
                <p>{{ __('所属顾问') }}:
                    <a href="{{route('users.show', $lead->user->id)}}">
                        {{$lead->user->name}}</a></p>
                <p>{{ __('建立时间') }}: {{ date('d F, Y, H:i', strtotime($lead->created_at))}} </p>
                @if($lead->days_until_contact < 2)
                    <p>{{ __('下一次跟进时间') }}: <span style="color:red;">{{date('d, F Y, H:i', strTotime($lead->contact_date))}}

                            @if($lead->status == 1) ({!! $lead->days_until_contact !!}) @endif</span> <i
                                class="glyphicon glyphicon-calendar" data-toggle="modal"
                                data-target="#ModalFollowUp"></i></p> <!--Remove days left if lead is completed-->

                @else
                    <p>{{ __('下一次跟进时间') }}: <span style="color:green;">{{date('d, F Y, H:i', strTotime($lead->contact_date))}}

                            @if($lead->status == 1) ({!! $lead->days_until_contact !!})<i
                                    class="glyphicon glyphicon-calendar" data-toggle="modal"
                                    data-target="#ModalFollowUp"></i>@endif</span></p>
                    <!--Remove days left if lead is completed-->
                @endif
                @if($lead->status == 1)
                    {{ __('状态') }}: <span class="label label-primary">{{ __('咨询中') }}</span>
                @elseif($lead->status == 2)
                    {{ __('状态') }}: <span class="label label-success">{{ __('完成咨询') }}</span>
                @elseif($lead->status == 3)
                    {{ __('状态') }}: <span class="label label-warning">{{ __('无兴趣') }}</span>
                @endif


            </div>

                {!! Form::model($lead, [
               'method' => 'PATCH',
                'url' => ['leads/updateassign', $lead->id],
                ]) !!}
                {!! Form::select('user_assigned_id', $users, null, ['class' => 'form-control ui search selection top right pointing search-select', 'id' => 'search-select']) !!}
                {!! Form::submit(__('更改所属顾问'), ['class' => 'btn btn-primary form-control closebtn']) !!}
                {!! Form::close() !!}
                </br>
                {!! Form::model($lead, [
               'method' => 'PATCH',
               'url' => ['leads/updatestatus', $lead->id],
               ]) !!}

                    {!! Form::select('status', array(
                 '1' => '进行中', '2' => '已完成','3' => '无兴趣'), null, ['class' => 'form-control'] )
              !!}
                    {!! Form::submit(__('更改咨询状态'), ['class' => 'btn btn-success form-control closebtn']) !!}
                    {!! Form::close() !!}


            <div class="activity-feed movedown">
                @foreach($lead->activity as $activity)

                    <div class="feed-item">
                        <div class="activity-date">{{date('d, F Y H:i', strTotime($activity->created_at))}}</div>
                        <div class="activity-text">{{$activity->text}}</div>

                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>


    <div class="modal fade" id="ModalFollowUp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ __('更改跟进时间') }}</h4>
                </div>

                <div class="modal-body">

                    {!! Form::model($lead, [
                      'method' => 'PATCH',
                      'route' => ['leads.followup', $lead->id],
                      ]) !!}
                    {!! Form::label('contact_date', __('下一次跟进时间'), ['class' => 'control-label']) !!}
                    {!! Form::date('contact_date', \Carbon\Carbon::now()->addDays(7), ['class' => 'form-control']) !!}
                    {!! Form::time('contact_time', '11:00', ['class' => 'form-control']) !!}


                    <div class="modal-footer">
                        <button type="button" class="btn btn-default col-lg-6"
                                data-dismiss="modal">{{ __('关闭') }}</button>
                        <div class="col-lg-6">
                            {!! Form::submit( __('更新跟进时间'), ['class' => 'btn btn-success form-control closebtn']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div></div>
@stop
       

   