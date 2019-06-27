<table class="table table-responsive" id="withDrawalLogs-table">
    <thead>
        <tr>
        <th>提现人</th>
        <th>提现金额</th>
        <th>支付宝账号</th>
        <th>状态</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($withDrawalLogs as $withDrawalLog)
    <?php $user = $withDrawalLog->user()->first(); ?>
        <tr>
            <td>{!! a_link($user->nickname,route('users.edit',$user->id)) !!}</td>
            <td>{!! $withDrawalLog->price !!}</td>
            <td>{!! $withDrawalLog->alipay_num !!}</td>
            <td>{!! $withDrawalLog->status !!}</td>
            <td>
                {!! Form::open(['route' => ['withDrawalLogs.destroy', $withDrawalLog->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                  {{--   <a href="{!! route('withDrawalLogs.show', [$withDrawalLog->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('withDrawalLogs.edit', [$withDrawalLog->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>