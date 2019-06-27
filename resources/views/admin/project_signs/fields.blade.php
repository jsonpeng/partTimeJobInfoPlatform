<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', '报名人姓名:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Self Des Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('self_des', '自我描述:') !!}
    {!! Form::textarea('self_des', null, ['class' => 'form-control']) !!}
</div>

<!-- Project Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('project_id', '报名项目Id:') !!}
    {!! Form::text('project_id', null, ['class' => 'form-control','readonly'=>'readonly']) !!}
</div>

<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', '报名人Id:') !!}
    {!! Form::text('user_id', null, ['class' => 'form-control','readonly'=>'readonly']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('mobile', '报名人电话:') !!}
    {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', '状态:') !!}
    <select name="status" class="form-control">
        <option value="已报名" @if(!empty($projectSign) && $projectSign->status == '已报名') selected="selected" @endif>已报名</option>
        <option value="已录用" @if(!empty($projectSign) && $projectSign->status == '已录用') selected="selected" @endif>已录用</option>
        <option value="已结算" @if(!empty($projectSign) && $projectSign->status == '已结算') selected="selected" @endif>已结算</option>
        <option value="已拒绝" @if(!empty($projectSign) && $projectSign->status == '已拒绝') selected="selected" @endif>已拒绝</option>
    </select>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('projectSigns.index') !!}" class="btn btn-default">返回</a>
</div>
