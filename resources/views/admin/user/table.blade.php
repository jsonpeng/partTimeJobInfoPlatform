<table class="table table-responsive" id="users-table">
    <thead>
        <tr>
        <th>头像</th>
        <th>微信昵称</th>
        <th>信誉积分</th>
        <th>账户余额</th>
        <th>用户类型</th>
        <th>openid</th>
        <th>手机号</th>
        <th>注册时间</th>
        <th>上一次使用校购的学校</th>
        <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
    <?php $min_credits = empty(getSettingValueByKey('user_min_credits')) ? 0 :getSettingValueByKey('user_min_credits'); ?>
        <tr>
            <td><img src="{!! $user->head_image !!}"  style="max-width: 100%;height: 80px;"/></td>
            <td>{!! $user->nickname !!}</td>
            <td>{!! tag($user->credits, $user->credits > $min_credits ? 'green' : 'red') !!}</td>
            <td>{!! $user->user_money !!}</td>
            <td>{!! $user->type!!} </td>
            <td>{!! $user->openid !!}</td>
            <td>{!! $user->mobile !!}</td>
            <td>{!! $user->created_at !!}</td>
            <td>{!! $user->school !!} </td>
            <td>
                {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('users.edit', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                  {{--   {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定要删除吗?')"]) !!} --}}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>