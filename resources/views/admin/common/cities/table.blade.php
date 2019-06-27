<table class="table table-responsive" id="cities-table">
    <thead>
        <tr>
        <th>地区</th>
        <th>所在层级</th>
        <th>上级地区</th>
        <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody class="cities_body">
    @foreach($cities as $cities)
        <tr class="cities">
            <td>{!! $cities->name !!}</td>
            <td>{!! $cities->level !!}</td>
            <td>{!! $cities->ParentCities !!}</td>
            <td>
                {!! Form::open(['route' => ['cities.destroy', $cities->id], 'method' => 'delete']) !!}
                <div class='btn-group'>

                    <a href="{!! route('cities.child.index', [$cities->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i>查看下级</a>
                    {{--  @if(!empty($cities->FreightDetail)) <a href="javascript:;" onclick="showFreightTemList({!! $cities->id !!})" class='btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i>查看对应的运费模板信息</a>@endif --}}
                    <a href="{!! route('cities.child.create', [$cities->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-plus"></i>新增下级</a>
                 {{--    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确认删除吗?')"]) !!} --}}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>