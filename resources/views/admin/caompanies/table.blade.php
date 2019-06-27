<table class="table table-responsive" id="caompanies-table">
    <thead>
        <tr>
        <th>企业名称</th>
        <th>联系人姓名</th>
        <th>电话</th>
        <th>微信</th>
        <th>省</th>
        <th>市</th>
        <th>区</th>
        <th>详细地址</th>
        <th>审核状态</th>
        <th>浏览量</th>
   {{--      <th>收藏量</th> --}}
        <th>纬度</th>
        <th>精度</th>
        <th>发布人</th>
        <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($caompanies as $caompany)
        <tr>
            <td>{!! $caompany->name !!}</td>
            <td>{!! $caompany->contact_man  !!}</td>
            <td>{!! $caompany->mobile !!}</td>
            <td>{!! $caompany->weixin !!}</td>
            <td>{!! getCitiesNameById($caompany->province) !!}</td>
            <td>{!! getCitiesNameById($caompany->city) !!}</td>
            <td>{!! getCitiesNameById($caompany->district) !!}</td>
            <td>{!! $caompany->detail !!}</td>
            <td>{!! $caompany->status !!}</td>
            <td>{!! $caompany->view !!}</td>
            {{-- <td>{!! $caompany->collect !!}</td> --}}
            <td>{!! $caompany->lat !!}</td>
            <td>{!! $caompany->lon !!}</td>
            <td>{!! $caompany->ReleaseUser !!}</td>
            <td>
                {!! Form::open(['route' => ['caompanies.destroy', $caompany->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
               {{--      <a href="{!! route('caompanies.show', [$caompany->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('caompanies.edit', [$caompany->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>