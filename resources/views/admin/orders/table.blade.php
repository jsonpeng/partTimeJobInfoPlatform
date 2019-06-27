<table class="table table-responsive" id="orders-table">
    <thead>
        <tr>
        <th>价格</th>
        <th>支付平台</th>
        <th>订单</th>
        <th>支付时间</th>
        <th>平台订单号</th>

        <th>订单类型</th>
        <th>下单用户</th>
        <th>下单会员等级</th>
        <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{!! $order->price !!}</td>
            <td>{!! $order->pay_platform !!}</td>
            <td>{!! $order->order_pay !!}</td>
            <td>{!! $order->paytime !!}</td>
            <td>{!! $order->out_trade_no !!}</td>
       
            <td>{!! $order->type !!}</td>
            <td>{!! $order->user()->first()->nickname !!}</td>
            <td>{!! $order->userLevel()->first()->name !!}</td>
            <td>
                {!! Form::open(['route' => ['orders.destroy', $order->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                {{--     <a href="{!! route('orders.show', [$order->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('orders.edit', [$order->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>