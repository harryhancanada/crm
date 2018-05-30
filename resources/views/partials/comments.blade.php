<?php $subject instanceof \App\Models\Task ? $instance = 'task' : $instance = 'lead' ?>

<div class="panel panel-primary shadow">
    <div class="panel-heading">
        <p>
            @if($instance == 'task')
             {{ __('申请名称') }}:{{$subject->title}}
            @else  {{ __('咨询名称') }}:{{$subject->title}}
                @endif
        </p>
    </div>
    <div class="panel-body">
        <p>详细内容：{{$subject->description }}</p>
        <hr>
        <p class="smalltext">{{ __('创建时间') }}:
            {{ date('d F, Y, H:i:s', strtotime($subject->created_at))}}
            @if($subject->updated_at != $subject->created_at)
                <br/>{{ __('修改时间') }}: {{date('d F, Y, H:i:s', strtotime($subject->updated_at))}}
            @endif</p>
    </div>
</div>

<div class="panel panel-primary shadow">
<div class="panel-heading">
    <p>备注</p>
</div>
    <div class="panel-body">
<?php $count = 0;?>
<?php $i = 1 ?>
@foreach($subject->comments as $comment)
    <div class="panel panel-primary shadow" style="margin-top:15px; padding-top:10px;">

        <div class="panel-body">
            <p class="smalltext"> #{{$i++}}</p>
            <p>  {{ $comment->description }}</p>

            <div style="text-align:right;">
                <p class="smalltext">{{ __('备注来自于') }}: <a
                        href="{{route('users.show', $comment->user->id)}}"> {{$comment->user->name}} </a>
           {{ __('备注添加时间') }}:
                {{ date('d F, Y, H:i:s', strtotime($comment->created_at))}}
                @if($comment->updated_at != $comment->created_at)
                        <br/>{{ __('被修改过') }} : {{date('d F, Y, H:i:s', strtotime($comment->updated_at))}}
                @endif</p>
            <form method="POST" action="{{action('CommentController@destroy', $comment->id)}}">
                <input type="hidden" name="_method" value="delete"/>
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                <input type="submit" class="btn-xs btn-danger" value="Delete" onClick="return confirm('你确定要彻底删除该条备注吗（不可恢复）?')" />
            </form>
            </div>

        </div>
    </div>
@endforeach
<br/>





@if($instance == 'task')
    {!! Form::open(array('url' => array('/comments/task',$subject->id, ))) !!}
    <div class="form-group">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'comment-field']) !!}

        {!! Form::submit( __('添加备注') , ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@else
    {!! Form::open(array('url' => array('/comments/lead',$lead->id, ))) !!}
    <div class="form-group">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'comment-field']) !!}

        {!! Form::submit( __('添加备注') , ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@endif
    </div>
</div>

<div class="panel panel-primary shadow">
    <div class="panel-heading">
        <p>文件管理</p>
    </div>
    <div class="panel-body">
        <table class="table">

            <div class="col-xs-10">
                <div class="form-group">
                    <form method="POST" action="{{ url('/clients/upload', $client->id )}}" class="dropzone" id="dropzone"
                          files="true" data-dz-removea
                          enctype="multipart/form-data"
                    >
                        <meta name="csrf-token" content="{{ csrf_token() }}">

                    </form>
                    <p><b>{{ __('文件大小限制') }}</b></p>
                </div>
            </div>
            <thead>
            <tr>
                <th>{{ __('文件名称') }}</th>
                <th>{{ __('文件大小') }}</th>
                <th>{{ __('上传时间') }}</th>
                <th>{{ __('操作选项') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($client->documents as $document)
                <tr>
                    <td>{{$document->file_display}}</td>
                    <td>{{$document->size}} <span class="moveright"> MB</span></td>
                    <td>{{$document->created_at}}</td>
                    <td><a  href="../files/{{$companyname}}/{{$document->path}}"
                            target="_blank" download><button class="btn btn-success">Open</button></a></td>
                    <td>
                        <a href="../files/{{$companyname}}/{{$document->path}}"
                           target="_blank" download><button class="btn btn-success">Download</button></a>
                    </td>
                    <td>
                        <form method="POST" action="{{action('DocumentsController@destroy', $document->id)}}">
                            <input type="hidden" name="_method" value="delete"/>
                            <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                            <input type="submit" class="btn btn-danger" value="Delete" onClick="return confirm('你确定要删除该文件（不可恢复）？')" />
                        </form>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>


@push('scripts')
    <script>
        $('#comment-field').atwho({
            at: "@",
            limit: 5, 
            delay: 400,
            callbacks: {
                remoteFilter: function (t, e) {
                    t.length <= 2 || $.getJSON("/users/users", {q: t}, function (t) {
                        e(t)
                    })
                }
            }
        })
    </script>
@endpush