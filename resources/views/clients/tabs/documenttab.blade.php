<div id="document" class="tab-pane fade">
    <table class="table">
        <h4>{{ __('所有文件') }}</h4>
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
		
		<input type="submit" class="btn btn-danger" value="Delete" onClick="return confirm('你确定要删除该文件（不可恢复）？')" />
                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
		</form>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>

</div>
