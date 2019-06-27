<table class="table table-responsive" id="feedBack-table">
    <thead>
        <tr>
        <th>发送人</th>
        <th>邮箱</th>
        <th>内容</th>
        <th>状态</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($feedBack as $feedBack)
        <?php $user= $feedBack->user()->first();?>
        <tr>
            <td>{!! a_link($user->nickname,route('users.edit',$user->id)) !!}</td>
            <td>{!! $feedBack->email !!}</td>
            <td>{!! $feedBack->content !!}</td>
            <td>{!! Form::model($feedBack, ['route' => ['feedBack.update', $feedBack->id], 'method' => 'patch']) !!}
                <a class='btn-group'>
                         <button class="btn btn-{!! $feedBack->status=='未读'?'danger':'success' !!} btn-xs"  type="submit" >{!! $feedBack->status !!}</button>
                         @if($feedBack->status=='未读') {!! Form::hidden('status','已读') !!} @else {!! Form::hidden('status','未读') !!}  @endif
                </a>
                 {!! Form::close() !!}</td>
            <td>
                {!! Form::open(['route' => ['feedBack.destroy', $feedBack->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
               {{--      <a href="{!! route('feedBack.show', [$feedBack->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                 {{--    <a href="{!! route('feedBack.edit', [$feedBack->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a> --}}
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>