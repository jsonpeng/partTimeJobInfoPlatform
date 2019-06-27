<table class="table table-responsive" id="industries-table">
    <thead>
        <tr>
        <th>兼职类型名称</th>
        <th>排序</th>
        <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($industries as $industry)
        <tr>
            <td>{!! $industry->name !!}</td>
            <td>{!! $industry->sort !!}</td>
            <td>
                {!! Form::open(['route' => ['industries.destroy', $industry->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                {{--     <a href="{!! route('industries.show', [$industry->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('industries.edit', [$industry->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>