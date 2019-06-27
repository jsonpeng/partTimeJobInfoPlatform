<!-- User Id Field -->
<div class="form-group col-sm-8">
    {!! Form::label('user_id', '提现人id:') !!}
    {!! Form::text('user_id', null, ['class' => 'form-control','readonly'=>'readonly']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-8">
    {!! Form::label('price', '提现金额:') !!}
    {!! Form::text('price', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-8">
    {!! Form::label('alipay_num', '支付宝账号:') !!}
    {!! Form::text('alipay_num', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-8">
    {!! Form::label('status', '状态:') !!}
   	<select name="status" class="form-control">
   		<option value="发起" @if(!empty($withDrawalLog) && $withDrawalLog->status == '发起') selected="selected" @endif>发起</option>
   		<option value="处理中" @if(!empty($withDrawalLog) && $withDrawalLog->status == '处理中') selected="selected" @endif>处理中</option>
   		<option value="已完成" @if(!empty($withDrawalLog) && $withDrawalLog->status == '已完成') selected="selected" @endif>已完成</option>
   	</select>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('withDrawalLogs.index') !!}" class="btn btn-default">返回</a>
</div>
