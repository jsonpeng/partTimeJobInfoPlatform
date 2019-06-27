<table class="table table-responsive" id="userLevels-table">
    <thead>
        <tr>
        <th>会员名称</th>
        <th>访问金额(元)</th>
        <th>售价</th>
        <th>提成比例</th>
        <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($userLevels as $userLevel)
    <?php $delete_content=!empty($userLevel->UsersList)?"'还有以下用户'.$userLevel->UsersList.'仍然在使用该会员等级,如果删除他们将被重置为注册会员,确定继续删除吗?'":"确定删除吗?";?>
        <tr>
            <td>{!! $userLevel->name !!}</td>
            <td>{!! $userLevel->amount !!}元</td>
            <td>{!! $userLevel->price !!}</td>
            <td>{!! $userLevel->rate !!}</td>
            <td>

             
             
                <div class='btn-group'>
                  
                    <a href="{!! route('userLevels.edit', [$userLevel->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    @if(array_key_exists('is_delete',$input)) 
                        {!! Form::open(['route' => ['userLevels.recorver', $userLevel->id], 'method' => 'delete']) !!}
                     {!! Form::button('恢复', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs' ]) !!}
                       {!! Form::close() !!}
                    @else
                       {!! Form::open(['route' => ['userLevels.destroy', $userLevel->id], 'method' => 'delete']) !!}
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm(".$delete_content.")" ]) !!}
                      {!! Form::close() !!}
                    @endif
                </div>
              
            </td>
        </tr>
    @endforeach
    </tbody>
</table>