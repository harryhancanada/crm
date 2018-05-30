<div class="col-md-6">
    <div class="panel panel-primary shadow  " >

            <a  class=" list-group-item" href="#clientinfor" data-toggle="collapse" style="color:white;"><span>学生姓名：{{$client->name}}
                    <i class="ion-chevron-up  arrow-up sidebar-arrow"></i></span></a>

        <div id="clientinfor" class="panel-body collapse" aria-expanded="false">

    <!--Client info leftside-->
    <div class="contactleft">
        @if($client->email != "")
                <!--MAIL-->
        <p><span class="glyphicon glyphicon-envelope" aria-hidden="true" data-toggle="tooltip"
                 title="{{ __('电子邮件') }}" data-placement="left"> </span> 电子邮件：
            <a href="mailto:{{$client->email}}" data-toggle="tooltip" data-placement="left">{{$client->email}}</a></p>
        @endif
        @if($client->primary_number != "")
                <!--Work Phone-->
        <p><span class="glyphicon glyphicon-headphones" aria-hidden="true" data-toggle="tooltip"
                 title=" {{ __('联系电话') }} " data-placement="left"> </span> 联系电话：
            <a href="tel:{{$client->work_number}}">{{$client->primary_number}}</a></p>
        @endif
        @if($client->secondary_number != "")
                <!--Secondary Phone-->
        <p><span class="glyphicon glyphicon-phone" aria-hidden="true" data-toggle="tooltip"
                 title="{{ __('备用电话') }}" data-placement="left"> </span> 备用电话：
            <a href="tel:{{$client->secondary_number}}">{{$client->secondary_number}}</a></p>
        @endif
        @if($client->address || $client->zipcode || $client->city != "")
                <!--Address-->
        <p><span class="glyphicon glyphicon-home" aria-hidden="true" data-toggle="tooltip"
                 title="{{ __('完整地址') }}" data-placement="left"> </span>  地址：{{$client->address}}
            <br/>{{$client->zipcode}} {{$client->city}}
        </p>
        @endif
    </div>

    <!--Client info leftside END-->
    <!--Client info rightside-->
    <div class="contactright">
        @if($client->company_name != "")
                <!--Company-->
        <p><span class="glyphicon glyphicon-star" aria-hidden="true" data-toggle="tooltip"
                 title="{{ __('就读学校') }}" data-placement="left"> </span>  现就读于 {{$client->company_name}}</p>
        @endif
        @if($client->vat != "")
                <!--Company-->
        <p><span class="glyphicon glyphicon-cloud" aria-hidden="true" data-toggle="tooltip"
                 title="{{ __('微信号码') }}" data-placement="left"> </span> 微信号码：{{$client->vat}}</p>
        @endif
        @if($client->industry != "")
                <!--Industry-->
        <p><span class="glyphicon glyphicon-briefcase" aria-hidden="true" data-toggle="tooltip"
                 title="{{ __('所属分类') }}"data-placement="left"> </span> 所属分类：{{app('App\Repositories\Client\ClientRepository')->getIndustries($client->industry)}}</p>
        @endif
        @if($client->company_type!= "")
                <!--Company Type-->
        <p><span class="glyphicon glyphicon-globe" aria-hidden="true" data-toggle="tooltip"
                 title="{{ __('签证状态') }}" data-placement="left"> </span>
            签证状态：{{$client->company_type}}</p>
        @endif
    </div>

        </div>
    </div>

</div>

<!--Client info rightside END-->
