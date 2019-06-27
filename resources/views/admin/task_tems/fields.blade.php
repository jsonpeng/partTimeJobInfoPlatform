<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', '任务模板名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Content Field -->
<div class="form-group col-sm-12">
    {!! Form::label('content', '任务模板描述:') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control','placeholder'=>'来__的同学，帮我__']) !!}
</div>

<!-- Tag Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tag', '标记区分符号:') !!}
    {!! Form::text('tag', '__', ['class' => 'form-control','readonly'=>'readonly']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('taskTems.index') !!}" class="btn btn-default">返回</a>
</div>
