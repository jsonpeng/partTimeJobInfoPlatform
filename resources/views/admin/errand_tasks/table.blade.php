<table class="table table-responsive" id="errandTasks-table">
    <thead>
        <tr>
        <th>任务名称</th>
        <th>发布人</th>
        <th>买手</th>
        <th>需要支付金额(元)</th>
        <th>平台提取金额(元)</th>
{{--         <th>等待买手确认</th> --}}
        <th>剩余时间</th>
        <th>希望送达时间</th>
        <th>发布人手机号</th>
        <th>状态</th>
        <th>支付状态</th>
        <th>发布地址</th>
        <th>发布学校</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($errandTasks as $errandTask)
    <?php $user = $errandTask->user()->first(); ?>
        <tr>
            <td>{!! $errandTask->name !!}</td>
            <td>{!! a_link($user->nickname,route('users.edit',$user->id)) !!}</td>
            <td>@if(!empty($errandTask->errand_id)) <?php $errander=user_by_id($errandTask->errand_id); ?>  {!! a_link($errander->nickname,route('users.edit',$errander->id)) !!} @else 暂无 @endif</td>
            <td>{!! $errandTask->pay_price !!}</td>
            <td>{!! $errandTask->platform_price !!}</td>
    {{--         <td>{!! $errandTask->wait_buyer_enter !!}</td> --}}
            <td>{!! $errandTask->current_remain_time.tag('('.$errandTask->cha_remain_time.')') !!}</td>
            <td>{!! $errandTask->current_wish_time.tag('('.$errandTask->cha_wish_time.')')  !!}</td>
            <td>{!! $errandTask->mobile !!}</td>
            <td>{!! $errandTask->status !!}</td>
            <td>{!! $errandTask->pay_status !!}</td>
            <td>{!! $errandTask->address !!}</td>
            <td>{!! $errandTask->school_name !!}</td>
            <td>
                {!! Form::open(['route' => ['errandTasks.destroy', $errandTask->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
              {{--       <a href="{!! route('errandTasks.show', [$errandTask->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('errandTasks.edit', [$errandTask->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>