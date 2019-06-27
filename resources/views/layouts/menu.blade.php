
<li class="header">系统</li>
  <li class="treeview @if(Request::is('zcjy/settings/setting*') || Request::is('zcjy')  || Request::is('zcjy/wechat/menu*') || Request::is('zcjy/wechat/reply*') || Request::is('zcjy/users*') || Request::is('zcjy/cities*')) active @endif " >
    <a href="#">
      <i class="fa fa-cog"></i>
      <span>系统管理</span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">

        <li class="{{ Request::is('zcjy/settings/setting*') || Request::is('zcjy') ? 'active' : '' }}">
            <a href="{!! route('settings.setting') !!}"><i class="fa fa-cog"></i><span>系统设置</span></a>
        </li>

     {{--    <li class="{{ Request::is('zcjy/wechat/menu*') || Request::is('zcjy/wechat/reply*') ? 'active' : '' }}">
            <a href="{!! route('wechat.menu') !!}"><i class="fa fa-commenting"></i><span>微信设置</span></a>
        </li> --}}

        <li class="{{ Request::is('zcjy/users*') ? 'active' : '' }}">
            <a href="{!! route('users.index') !!}"><i class="fa fa-user"></i><span>用户管理</span></a>
        </li>

        <li class="{{ Request::is('zcjy/cities*') ? 'active' : '' }}">
            <a href="{!! route('cities.index') !!}"><i class="fa fa-arrows"></i><span>城市设置</span></a>
        </li>
    </ul>
</li>
<li class="header">统计</li>
<li class="{{ Request::is('zcjy/statics*') ? 'active' : '' }}">
    <a href="{!! route('statics.errand') !!}"><i class="fa fa-pie-chart"></i><span>校购统计</span></a>
</li>
<li class="header">兼职</li>
  <li class="treeview @if(Request::is('zcjy/industries*') || Request::is('zcjy/projects*') || Request::is('zcjy/caompanies*')  || Request::is('zcjy/companyErrors*')  || Request::is('zcjy/projectSigns*')) active @endif " >
    <a href="#">
      <i class="fa fa-fire"></i>
      <span>兼职管理</span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">

        <li class="{{ Request::is('zcjy/industries*') ? 'active' : '' }}">
            <a href="{!! route('industries.index') !!}"><i class="fa fa-bookmark"></i><span>兼职类型</span></a>
        </li>

        <li class="{{ Request::is('zcjy/projects*') ? 'active' : '' }}">
            <a href="{!! route('projects.index') !!}"><i class="fa fa-edit"></i><span>兼职管理</span></a>
        </li>

         <li class="{{ Request::is('zcjy/projectSigns*') ? 'active' : '' }}">
            <a href="{!! route('projectSigns.index') !!}"><i class="fa fa-edit"></i><span>兼职报名列表</span></a>
        </li>

        <li class="{{ Request::is('zcjy/caompanies*') ? 'active' : '' }}">
            <a href="{!! route('caompanies.index') !!}"><i class="fa  fa-home"></i><span>企业管理</span></a>
        </li>

        <li class="{{ Request::is('zcjy/companyErrors*') ? 'active' : '' }}">
            <a href="{!! route('companyErrors.index') !!}"><i class="fa fa-commenting-o"></i><span>投诉列表</span></a>
        </li>
    </ul>
 </li>

{{-- <li class="{{ Request::is('zcjy/orders*') ? 'active' : '' }}">
    <a href="{!! route('orders.index') !!}"><i class="fa fa-edit"></i><span>订单</span></a>
</li> --}}


<li class="header">校购</li>
  <li class="treeview @if(Request::is('zcjy/taskTems*') || Request::is('zcjy/errandTasks*') || Request::is('zcjy/schools*') || Request::is('zcjy/errandErrors*')) active @endif " >
    <a href="#">
      <i class="fa fa-cog"></i>
      <span>校购管理</span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
      <li class="{{ Request::is('zcjy/taskTems*') ? 'active' : '' }}">
          <a href="{!! route('taskTems.index') !!}"><i class="fa fa-edit"></i><span>任务模板描述管理</span></a>
      </li>
      <li class="{{ Request::is('zcjy/errandTasks*') ? 'active' : '' }}">
          <a href="{!! route('errandTasks.index') !!}"><i class="fa fa-edit"></i><span>跑腿任务管理</span></a>
      </li>
      <li class="{{ Request::is('zcjy/schools*') ? 'active' : '' }}">
          <a href="{!! route('schools.index') !!}"><i class="fa fa-edit"></i><span>使用校购的学校</span></a>
      </li>
      <li class="{{ Request::is('zcjy/errandErrors*') ? 'active' : '' }}">
          <a href="{!! route('errandErrors.index') !!}"><i class="fa fa-commenting-o"></i><span>投诉列表</span></a>
      </li>
     </ul>
   </li>

<li class="{{ Request::is('zcjy/withDrawalLogs*') ? 'active' : '' }}">
    <a href="{!! route('withDrawalLogs.index') !!}"><i class="fa fa-archive"></i><span>用户提现记录</span></a>
</li>

<li class="{{ Request::is('zcjy/feedBack*') ? 'active' : '' }}">
    <a href="{!! route('feedBack.index') !!}"><i class="fa fa-commenting-o"></i><span>意见反馈</span></a>
</li>

<li class="">
    <a href="/guide/guide.pdf" ><i class="fa fa-laptop"></i><span>操作说明</span></a>
</li>

<li class="">
    <a href="javascript:;" id="refresh"><i class="fa fa-refresh"></i><span>清理缓存</span></a>
</li>




{{-- <li class="{{ Request::is('creaditsLogs*') ? 'active' : '' }}">
    <a href="{!! route('creaditsLogs.index') !!}"><i class="fa fa-edit"></i><span>Creadits Logs</span></a>
</li> --}}




{{-- 
<li class="{{ Request::is('errandImages*') ? 'active' : '' }}">
    <a href="{!! route('errandImages.index') !!}"><i class="fa fa-edit"></i><span>Errand Images</span></a>
</li> --}}
{{-- 
<li class="{{ Request::is('refundLogs*') ? 'active' : '' }}">
    <a href="{!! route('refundLogs.index') !!}"><i class="fa fa-edit"></i><span>Refund Logs</span></a>
</li>
 --}}
