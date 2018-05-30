<div class="modal fade" id="ModalTimer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                {{ __('进度及缴费管理') }}
                    (申请:{{$title}})</h4>
                
            </div>
            @if($type == 'task')
           {!! Form::open([
                'method' => 'post',
                'url' => ['tasks/updatetime', $id],
            ]) !!}
            @else
              {!! Form::open([
                'method' => 'post',
                 'route' => ['invoice.new.item', $id],
            ]) !!}
            @endif
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('title', __('名称'), ['class' => 'control-label']) !!}
                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' =>  __('')]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('comment',  __('详细说明'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('comment', null, ['class' => 'form-control', 'placeholder' => __('')]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('price', __('收取费用'), ['class' => 'control-label']) !!}
                    {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('quantity', __('花费时间/小时'), ['class' => 'control-label']) !!}
                    {!! Form::text('quantity', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('type', __('种类'), ['class' => 'control-label']) !!}
                    {!! Form::text('type', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default col-lg-6"
                        data-dismiss="modal">{{ __('Close') }}</button>
                <div class="col-lg-6">
                    {!! Form::submit( __('添加新进度'), ['class' => 'btn btn-success form-control closebtn']) !!}
                </div>
              
            </div>
              {!! Form::close() !!}
        </div>
    </div>
</div>