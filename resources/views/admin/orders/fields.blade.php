<!-- Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', '价格:') !!}
    {!! Form::text('price', null, ['class' => 'form-control']) !!}
</div>

<!-- Pay Platform Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pay_platform', '支付平台:') !!}
    {!! Form::text('pay_platform', null, ['class' => 'form-control']) !!}
</div>

<!-- Order Pay Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order_pay', '支付状态:') !!}
    {!! Form::text('order_pay', null, ['class' => 'form-control']) !!}
</div>

<!-- Paytime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('paytime', '支付时间:') !!}
    {!! Form::text('paytime', null, ['class' => 'form-control']) !!}
</div>

{{-- <!-- Pay No Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pay_no', '平台订单号:') !!}
    {!! Form::text('pay_no', null, ['class' => 'form-control']) !!}
</div> --}}

<!-- Out Trade No Field -->
<div class="form-group col-sm-6">
    {!! Form::label('out_trade_no', '订单号:') !!}
    {!! Form::text('out_trade_no', null, ['class' => 'form-control']) !!}
</div>

<!-- Remark Field -->
{{-- <div class="form-group col-sm-6">
    {!! Form::label('remark', '用户留言:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div> --}}

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', '订单类型:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', '下单用户:') !!}
    {!! Form::number('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- User Level Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_level_id', '会员等级:') !!}
    {!! Form::number('user_level_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('orders.index') !!}" class="btn btn-default">返回</a>
</div>
