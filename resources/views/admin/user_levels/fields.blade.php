<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', '会员名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-12">
    {!! Form::label('amount', '访问金额(元):') !!}
    {!! Form::number('amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-12">
    {!! Form::label('price', '售价:') !!}
    {!! Form::text('price', null, ['class' => 'form-control']) !!}
</div>

<!-- Rate Field -->
<div class="form-group col-sm-12">
    {!! Form::label('rate', '提成比例:') !!}
    {!! Form::number('rate', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('userLevels.index') !!}" class="btn btn-default">返回</a>
</div>
