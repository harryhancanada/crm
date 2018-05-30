<div id="task" class="tab-pane fade in active">
    <div class="boxspace">
        <table class="table table-hover">
            <h4>{{ __('所有申请') }}</h4>
            <thead>
            <thead>
            <tr>
            <th>{{ __('申请名称') }}</th>
            <th>{{ __('所属顾问') }}</th>
            <th>{{ __('建立时间') }}</th>
            <th>{{ __('截止时间') }}</th>
            <th>{{ __('申请状态') }}</th>
            <th>{{ __('操作选项') }}</th>
            <th>{{ __('') }}</th>
                <th><a href="{{ route('tasks.create', ['client' => $client->id])}}">
                        <button class="btn btn-success">{{ __('新建申请') }}</button>
                    </a></th>

            </tr>
            </thead>
            <tbody>
            <?php  $tr = ""; ?>
            @foreach($client->tasks as $task)
                @if($task->status == 1)
                    <?php  $tr = '#adebad'; ?>
                @elseif($task->status == 2)
                    <?php $tr = '#ff6666'; ?>
                @endif
                <tr style="background-color:<?php echo $tr;?>">

                    <td><a href="{{ route('tasks.show', $task->id) }}">{{$task->title}} </a></td>
                    <td>
                        <div class="popoverOption"
                             rel="popover"
                             data-placement="left"
                             data-html="true"
                             data-original-title="<span class='glyphicon glyphicon-user' aria-hidden='true'> </span> {{$task->user->name}}">
                            <div id="popover_content_wrapper" style="display:none; width:250px;">
                                <img src='http://placehold.it/350x150' height='80px' width='80px'
                                     style="float:left; margin-bottom:5px;"/>
                                <p class="popovertext">
                                    <span class="glyphicon glyphicon-envelope" aria-hidden="true"> </span>
                                    <a href="mailto:{{$task->user->email}}">
                                        {{$task->user->email}}<br/>
                                    </a>
                                    <span class="glyphicon glyphicon-headphones" aria-hidden="true"> </span>
                                    <a href="mailto:{{$task->user->work_number}}">
                                    {{$task->user->work_number}}</p>
                                </a>

                            </div>
                            <a href="{{route('users.show', $task->user->id)}}"> {{$task->user->name}}</a>

                        </div> <!--Shows users assigned to task -->
                    </td>
                    <td>{{date('d, M Y, H:i', strTotime($task->created_at))}}  </td>
                    <td>{{date('d, M Y', strTotime($task->deadline))}}
                        @if($task->status == 1)({{ $task->days_until_deadline }}) @endif</td>
                    <td>
                @if($task->status == 1)
                     <span class="label label-primary">{{ __('进行中') }}</span>
                @endif

                @if($task->status == 2)
                     <span class="label label-success">{{ __('已完成') }}</span>
                @endif

                @if($task->status == 3)
                    <span class="label label-danger">{{ __('无效') }}</span>
                @endif

                @if($task->status == 4)
                    <span class="label label-warning">{{ __('暂停') }}</span>
                @endif
                        
                    </td>
                    <td><a href="{{route("tasks.show", $task->id) }}" class="btn btn-success"> Edit</a></td>
                    
                    <td><form action="{{ route('tasks.destroy', $task->id)}}" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="submit" name="submit" value="Delete" class="btn btn-danger" onClick="return confirm('你确定要删除该条申请吗（不可恢复）？')" />

            {{csrf_field()}}
            </form></td>
            <td></td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>