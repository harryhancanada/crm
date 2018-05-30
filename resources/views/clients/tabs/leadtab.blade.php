<div id="lead" class="tab-pane fade">
    <div class="boxspace">
        <table class="table table-hover">
            <h4>{{ __('咨询记录') }}</h4>
            <thead>
            <thead>
            <tr>
                <th>{{ __('主题') }}</th>
                <th>{{ __('负责顾问') }}</th>
                <th>{{ __('咨询时间') }}</th>
                <th>{{ __('截止时间') }}</th>
                <th>{{ __('咨询状态') }}</th>
                <th>{{ __('操作选项') }}</th>

                <th><a href="{{ route('leads.create', ['client' => $client->id])}}">
                        <button class="btn btn-success">{{ __('新建咨询') }}</button>
                    </a></th>

            </tr>
            </thead>
            <tbody>
            <?php  $tr = ""; ?>
          
            @foreach($client->leads as $lead)
                @if($lead->status == 1)
                    <?php  $tr = '#adebad'; ?>
                @elseif($lead->status == 2)
                    <?php $tr = '#ff6666'; ?>
                @endif
                <tr style="background-color:<?php echo $tr;?>">

                    <td><a href="{{ route('leads.show', $lead->id) }}">{{$lead->title}} </a></td>
                    <td>
                        <div class="popoverOption"
                             rel="popover"
                             data-placement="left"
                             data-html="true"
                             data-original-title="<span class='glyphicon glyphicon-user' aria-hidden='true'> </span> {{$lead->user->name}}">
                            <div id="popover_content_wrapper" style="display:none; width:250px;">
                                <img src='http://placehold.it/350x150' height='80px' width='80px'
                                     style="float:left; margin-bottom:5px;"/>
                                <p class="popovertext">
                                    <span class="glyphicon glyphicon-envelope" aria-hidden="true"> </span>
                                    <a href="mailto:{{$lead->user->email}}">
                                        {{$lead->user->email}}<br/>
                                    </a>
                                    <span class="glyphicon glyphicon-headphones" aria-hidden="true"> </span>
                                    <a href="mailto:{{$lead->user->work_number}}">
                                    {{$lead->user->work_number}}</p>
                                </a>

                            </div>
                            <a href="{{route('users.show', $lead->user->id)}}"> {{$lead->user->name}}</a>

                        </div> <!--Shows users assigned to lead -->
                    </td>
                    <td>{{date('d, M Y, H:i', strTotime($lead->contact_date))}} </td>
                    <td>{{date('d, M Y', strTotime($lead->contact_date))}}
                        @if($lead->status == 1)({{ $lead->days_until_contact }})@endif </td>
                    <td>
                        @if($lead->status == 1)
                        <span class="label label-primary">{{ __('咨询中') }}</span>
                        @elseif($lead->status == 2)
                         <span class="label label-success">{{ __('完成咨询') }}</span>
                        @elseif($lead->status == 3)
                        <span class="label label-warning">{{ __('无兴趣') }}</span>
                        @endif
                    </td>
                    <td><a href="{{route("leads.show", $lead->id) }}" class="btn btn-success"> Edit</a></td>
                    <td><form action="{{ route('leads.destroy', $lead->id) }}" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="submit" name="submit" value="Delete" class="btn btn-danger" onClick="return confirm('你确定要彻底删除该条咨询吗（不可恢复）?')">

            {{csrf_field()}}
            </form></td>
            <td></td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>
</div>