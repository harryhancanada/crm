<div class="col-lg-6">
    <div class="panel panel-primary shadow">

            <a  class=" list-group-item" href="#userinfo" data-toggle="collapse" style="color:white;"><span>顾问姓名：{{ $contact->nameAndDepartment }}<i class="ion-chevron-up  arrow-up sidebar-arrow"></i>    </span></a>

        <div id="userinfo" class="panel-body collapse" aria-expanded="false">

    <div class="profilepic"><img class="profilepicsize" src="../{{ $contact->avatar }}" /></div>
    <h1> </h1>

    <!--MAIL-->
    <p><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
    <!--Work Phone-->
    <p><span class="glyphicon glyphicon-headphones" aria-hidden="true"></span>
        <a href="tel:{{ $contact->work_number }}">{{ $contact->work_number }}</a></p>

    <!--Personal Phone-->
    <p><span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
        <a href="tel:{{ $contact->personal_number }}">{{ $contact->personal_number }}</a></p>

    <!--Address-->
    <p><span class="glyphicon glyphicon-home" aria-hidden="true"></span>
        {{ $contact->address }}  </p>
</div>
    </div></div>