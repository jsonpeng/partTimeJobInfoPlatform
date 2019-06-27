<!-- User Id Field -->
<div class="form-group col-sm-8">
    {!! Form::label('user_id', '发布人id:') !!}
    {!! Form::text('user_id', null, ['class' => 'form-control','readonly'=>'readonly']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-8">
    {!! Form::label('email', '邮箱:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Content Field -->
<div class="form-group col-sm-8">
    {!! Form::label('content', '反馈内容:') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-8">
    {!! Form::label('status', '状态:') !!}
    <select name="status" class="form-control">
        <option value="未读" @if(!empty($feedBack) && $feedBack->status == '未读') selected="selected" @endif>未读</option>
        <option value="已读" @if(!empty($feedBack) && $feedBack->status == '已读') selected="selected" @endif>已读</option>
    </select>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('feedBack.index') !!}" class="btn btn-default">返回</a>
</div>
