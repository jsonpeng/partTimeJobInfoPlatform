<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', '兼职类型名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Sort Field -->
<div class="form-group col-sm-12">
    {!! Form::label('sort', '排序权重:(权重越高，排序越靠前)') !!}
    {!! Form::number('sort', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('industries.index') !!}" class="btn btn-default">返回</a>
</div>
