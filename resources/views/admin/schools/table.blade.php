<table class="table table-responsive" id="schools-table">
    <thead>
        <tr>
        <th>学校名称</th>
        <th>学校地址</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($schools as $school)
        <tr>
            <td>{!! $school->name !!}</td>
            <td>{!! $school->address !!}</td>
            <td>
                {!! Form::open(['route' => ['schools.destroy', $school->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('schools.show', [$school->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('schools.edit', [$school->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>