<table class="table table-responsive" id="taskTems-table">
    <thead>
        <tr>
        <th>任务模板名称</th>
        <th>任务模板描述</th>
        <th>标记区分符号</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($taskTems as $taskTem)
        <tr>
            <td>{!! $taskTem->name !!}</td>
            <td>{!! $taskTem->content !!}</td>
            <td>{!! $taskTem->tag !!}</td>
            <td>
                {!! Form::open(['route' => ['taskTems.destroy', $taskTem->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                 {{--    <a href="{!! route('taskTems.show', [$taskTem->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('taskTems.edit', [$taskTem->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>