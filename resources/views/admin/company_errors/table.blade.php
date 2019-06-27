<table class="table table-responsive" id="companyErrors-table">
    <thead>
        <tr>
        <th>投诉原因</th>
        <th>投诉内容</th>
        <th>状态</th>
        <th>投诉人</th>
        <th>投诉项目(兼职)</th>
        <th>发起</th>
        <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($companyErrors as $companyError)
        <?php 
        $user= optional($companyError->user()->first());
        $project = optional($companyError->project()->first());
        ?>
        <tr>
            <td>{!! $companyError->type !!}</td>
            <td>{!! $companyError->reason !!}</td>
            <td>  {!! Form::model($companyError, ['route' => ['companyErrors.update', $companyError->id], 'method' => 'patch']) !!}
                <a class='btn-group'>
                         <button class="btn btn-{!! $companyError->status=='审核中'?'danger':'success' !!} btn-xs" @if($companyError->status=='审核中') type="submit" onclick="return confirm('确定要审核通过吗?对应投诉用户将会扣除积分')" @else type="button" @endif>{!! $companyError->status !!}</button>
                         @if($companyError->status=='审核中') {!! Form::hidden('status','已通过') !!} @endif
                </a>
                 {!! Form::close() !!}
            </td>
            <td>{!! a_link($user->nickname,route('users.edit',$user->id)) !!}</td>
            <td>{!! a_link($project->name,route('projects.edit',$user->id)) !!}</td>
            <td>{!! $companyError->send_type !!}</td>
            <td>
                {!! Form::open(['route' => ['companyErrors.destroy', $companyError->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                  {{--   <a href="{!! route('companyErrors.show', [$companyError->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('companyErrors.edit', [$companyError->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a> --}}
                      
                     {{--    <a class='btn-group'>
                         <span class="btn btn-{!! !$companyError->status?'danger':'success' !!} btn-xs" onclick="actionList(this,{!! $companyError->id !!})">{!! !$companyError->status?'未读':'已读' !!}</span>
                        </a> --}}
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定要删除吗?')"]) !!}
                      </div>
                </div>
                {!! Form::close() !!}
            </td>
        </tr>

    @endforeach
    </tbody>
</table>