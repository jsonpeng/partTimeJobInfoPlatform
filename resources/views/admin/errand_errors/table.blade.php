<table class="table table-responsive" id="errandErrors-table">
    <thead>
        <tr>
        <th>投诉原因</th>
        <th>投诉内容</th>
        <th>买手</th>
        <th>发起人</th>
        <th>投诉项目(跑腿任务)</th>
        <th>状态</th>
        <th>发起</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($errandErrors as $errandError)
    <?php 
    $publisher = optional($errandError->publisher()->first());
    $errander = optional($errandError->errander()->first());
    $task = optional($errandError->task()->first());
    ?>
        <tr>
            <td>{!! $errandError->type !!}</td>
            <td>{!! $errandError->reason !!}</td>
            <td>{!! a_link($errander->nickname,route('users.edit',$errander->id)) !!}</td>
            <td>{!! a_link($publisher->nickname,route('users.edit',$publisher->id)) !!}</td>
            <td>{!! a_link($task->name,route('errandTasks.edit',$task->id)) !!}</td>
            <td>
                 {!! Form::model($errandError, ['route' => ['errandErrors.update', $errandError->id], 'method' => 'patch']) !!}
                <a class='btn-group'>
                         <button class="btn btn-{!! $errandError->status=='审核中'?'danger':'success' !!} btn-xs" @if($errandError->status=='审核中') type="submit" onclick="return confirm('确定要审核通过吗?对应投诉用户将会扣除积分')" @else type="button" @endif>{!! $errandError->status !!}</button>
                         @if($errandError->status=='审核中') {!! Form::hidden('status','已通过') !!} @endif
                </a>
                 {!! Form::close() !!}
            </td>
            <td>{!! $errandError->send_type !!}</td>
            <td>
                {!! Form::open(['route' => ['errandErrors.destroy', $errandError->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                  {{--   <a href="{!! route('errandErrors.show', [$errandError->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('errandErrors.edit', [$errandError->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a> --}}
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>