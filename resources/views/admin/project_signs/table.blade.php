<table class="table table-responsive" id="projectSigns-table">
    <thead>
        <tr>
        <th>报名人微信头像</th>
        <th>报名人名称</th>
        <th>报名人微信昵称</th>
        <th>报名兼职</th>
        <th>报名人电话</th>
        <th>状态</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($projectSigns as $projectSign)
            <?php $project = optional($projectSign->project()->first()); $user= optional($projectSign->user()->first());?>
        <tr>
            <td><img src="{!! $user->head_image !!}"  style="max-width: 100%;height: 80px;"/></td>
            <td>{!! $projectSign->name !!}</td>
            <th>{!! a_link($user->nickname,route('users.edit',$user->id)) !!} </th>
            <td>{!! a_link($project->name,route('projects.edit',$projectSign->project_id)) !!}</td>
            <td>{!! $projectSign->mobile !!}</td>
            <td>{!! $projectSign->status !!}</td>
            <td>
                {!! Form::open(['route' => ['projectSigns.destroy', $projectSign->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
               {{--      <a href="{!! route('projectSigns.show', [$projectSign->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('projectSigns.edit', [$projectSign->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>