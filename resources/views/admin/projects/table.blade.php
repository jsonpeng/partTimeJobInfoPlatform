<table class="table table-responsive" id="projects-table">
    <thead>
        <tr>
        <th>兼职名称</th>
        <th>兼职类型</th>
        <th>电话</th>
        <th>兼职金额(元)</th>
        <th>兼职时间(金额)</th>
        <th>发布类型</th>
        <th>地址</th>
        <th>审核状态</th>
        <th>付款状态</th>
        <th>发布人</th>
        <th>发布人企业</th>
        <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($projects as $project)
        <?php $company = optional($project->ReleaseUserCompany); ?>
        <tr>
            <td>{!! $project->name !!}</td>
            <td>{!! $project->industriesShow !!}</td>
            <td>{!! $project->mobile !!}</td>
            <td>{!! $project->money !!}元</td>
            <td>{!! $project->time_set !!}</td>
            <td>{!! $project->type !!}</td>
            <td>{!! $project->address !!}</td>
            <td><span class="btn btn-{!! $project->status=='通过'?'success':'danger' !!} btn-xs">{!! $project->status !!}</span></td>
            <td><span class="btn btn-{!! $project->pay_status=='已付款'  ?'success' : 'danger' !!} btn-xs">{!! $project->pay_status !!}</span></td>
            <td>{!! $project->ReleaseUser !!}</td>
            <td>{!! a_link($company->name,route('caompanies.edit',$company->id)) !!}</td>
            <td>
                {!! Form::open(['route' => ['projects.destroy', $project->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                   {{--  <a href="{!! route('projects.show', [$project->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('projects.edit', [$project->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>