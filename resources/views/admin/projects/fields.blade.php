
<div class="form-group col-sm-8">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">兼职详情</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <!-- Name Field -->
            <div class="form-group">
                {!! Form::label('name', '兼职名称:') !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                {!! Form::hidden('caompanie_id',null,['class' => 'form-control']) !!}
                @if(empty($project))
               {!! Form::hidden('user_id',1, ['class' => 'form-control']) !!}
               @else
                {!! Form::hidden('user_id',null, ['class' => 'form-control']) !!}
               @endif
            </div>



            <div class="form-group" style="overflow: hidden;">
            {{--  {!! Form::label('industry_id', '所属行业:') !!} --}}
                    @foreach ($industries as $category)
                    <div style="float: left; margin-right: 20px; ">
                        <label>
                            {!! Form::checkbox('industries[]', $category->id, in_array($category->id, $selectedIndustries), ['class' => 'select_cat']) !!}
                                {!! $category->name !!}
                        </label>
                    </br>
                    </div>
                    @endforeach

                    @if(count($industries)==0)
                    <a href="{!! route('industries.create') !!}">添加兼职类型</a>
                    @endif
            </div>

            <!-- Money Field -->
            <div class="form-group">
                {!! Form::label('money', '兼职基本工资金额(元):') !!}
                {!! Form::text('money', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('time_set', '金额时间:') !!}
                   <select class="form-control"  name="time_set">
             
                            <option  value="小时" @if(!empty($project)) {!! $project->time_set=='小时'?'selected':'' !!} @endif>小时</option>
                            <option  value="天" @if(!empty($project)) {!! $project->time_set=='天'?'selected':'' !!} @endif>天</option>
                            <option  value="周" @if(!empty($project)) {!! $project->time_set=='周'?'selected':'' !!} @endif>周</option>
                            <option  value="月" @if(!empty($project)) {!! $project->time_set=='月'?'selected':'' !!} @endif>月</option>
               
                   </select>
            </div>
            
            <!-- Type Field -->
            <div class="form-group">
                {!! Form::label('type', '发布类型:') !!}
                   <select class="form-control"  name="type">
                    @if(empty($project))
                            <option  value="管理员" @if(!empty($project)) {!! $project->type=='管理员'?'selected':'' !!} @endif>管理员</option>
                    @else
                            <option  value="个人" @if(!empty($project)) {!! $project->type=='个人'?'selected':'' !!} @endif>个人</option>
                            <option  value="企业" @if(!empty($project)) {!! $project->type=='企业'?'selected':'' !!} @endif>企业</option>
                            <option  value="管理员" @if(!empty($project)) {!! $project->type=='管理员'?'selected':'' !!} @endif>管理员</option>
                    @endif
                   </select>
            </div>

            <div class="form-group" >
                {!! Form::label('start_time', '开始时间:') !!}
                <div class='input-group date' id='datetimepicker_begin'>
                    {!! Form::text('start_time', null, ['class' => 'form-control', 'maxlength' => '10']) !!}
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>

            <div class="form-group" >
                {!! Form::label('end_time', '结束时间:') !!}
                <div class='input-group date' id='datetimepicker_end'>
                    {!! Form::text('end_time', null, ['class' => 'form-control', 'maxlength' => '10']) !!}
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-6" >
                    {!! Form::label('morning_start_time', '工作开始时间:') !!}<span class="required">(必填)</span>
                    <div class='input-group date' id='morning_start_time'>
                        {!! Form::text('morning_start_time', null, ['class' => 'form-control', 'maxlength' => '10']) !!}
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group col-sm-6" >
                    {!! Form::label('morning_end_time', '工作结束时间:') !!}<span class="required">(必填)</span>
                    <div class='input-group date' id='morning_end_time'>
                        {!! Form::text('morning_end_time', null, ['class' => 'form-control', 'maxlength' => '10']) !!}
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                 {!! Form::hidden('afternoon_end_time', '11', ['class' => 'form-control', 'maxlength' => '10']) !!}
                {!! Form::hidden('afternoon_start_time', '11', ['class' => 'form-control', 'maxlength' => '10']) !!}
             {{--     <div class="form-group col-sm-6" >
                    {!! Form::label('morning_end_time', '上午结束时间:') !!}
                    <div class='input-group date' id='morning_end_time'>
                        {!! Form::text('morning_end_time', '11', ['class' => 'form-control', 'maxlength' => '10']) !!}
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div> --}}
            </div>


          {{--   <div class="row">
                <div class="form-group col-sm-6" >
                    {!! Form::label('afternoon_start_time', '下午开始时间:') !!}
                    <div class='input-group date' id='afternoon_start_time'>
                      
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            
            </div> --}}

            <!-- Detail Field -->
            <div class="form-group">
                {!! Form::label('detail', '工作内容:') !!}
                {!! Form::textarea('detail', null, ['class' => 'form-control']) !!}
            </div>

           <div class="form-group">

                      <section class="content-header" style="height: 50px; padding: 0; padding-top: 15px;">
                      <h1 class="pull-left" style="font-size: 14px; font-weight: bold; line-height: 34px;padding-bottom: 0px;">兼职展示图片</h1>

                       <h3 class="pull-right" style="margin: 0">
                                <input type="hidden" name="addimage" value="" id="project_image">
                                <div class="pull-right" style="margin: 0">
                                    <a data-toggle="modal" href="javascript:;" data-target="#myModal" class="btn btn-primary" type="button" onclick="projectImage('project_image')">添加兼职展示图片</a>
                                </div>
                        </h3>
                    </section>

                    <div class="images" style="display:@if(count($images)) block @else none @endif;">
                            <?php $i=0;?>
                            @foreach ($images as $image)
                            <div class="image-item" id="project_image_{{ $i }}">
                                <img src="{!! $image->
                                url !!}" alt="" style="max-width: 100%;">
                                <div class="tr">
                                    <div class="btn btn-danger btn-xs" onclick="deletePic({{ $i }})">删除</div>
                                </div>
                                 <input type='hidden' name='project_images[]' value='{!! $image->
                                url !!}'>
                            </div>
                            <?php $i++;?>
                            @endforeach
                    </div>

            </div>

            <div class="form-group group">
                <select name="province" id="province" >
                    <option value="0" @if(empty($project)) selected="selected" @endif>请选择省份</option>
                    @foreach($cities_level1 as $item)
                        <option value="{!! $item->id !!}" @if(!empty($project)) @if($project->province==$item->id) selected="selected" @endif @endif>{!! $item->name !!}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group group">
                <select  name="city" id="city">
                        <option value="0" @if(empty($project)) selected="selected" @endif>请选择城市</option>
                        @foreach ($cities_level2 as $item)
                            <option value="{!! $item->id !!}" @if(!empty($project)) @if($project->city==$item->id) selected="selected" @endif @endif>{!! $item->name !!}</option>
                        @endforeach
                </select>
            </div>

            <div class="form-group group">
                <select  name="district"  id="district"  data-type="project">
                        <option value="0" @if(empty($project)) selected="selected" @endif>请选择区域</option>
                        @foreach ($cities_level3 as $item)
                            <option value="{!! $item->id !!}" @if(!empty($project)) @if($project->district==$item->id) selected="selected" @endif @endif>{!! $item->name !!}</option>
                        @endforeach
                </select>
            </div>

            <!-- Address Field -->
            <div class="form-group">
                {!! Form::label('address', '地址:') !!}
                {!! Form::text('address', null, ['class' => 'form-control']) !!}
                 <a class="inline-block pd10" onclick="openMap(1)">在地图中设定</a>
            </div>

        </div>

    </div>
</div>

<div class="form-group col-sm-4">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">发布设置</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('projects.index') !!}" class="btn btn-default">返回</a>
        </div>

    </div>

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">状态设置</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            
             <div class="form-group">
                {!! Form::label('is_top', '是否置顶:') !!}
                  <select class="form-control"  name="is_top">
                        <option  value="0" @if(!empty($project)) {!! $project->is_top==0?'selected':'' !!} @endif>否</option>
                        <option  value="1" @if(!empty($project)) {!! $project->is_top==1?'selected':'' !!} @endif>是</option>
                 
                   </select>
            </div>
             <!-- Auth Status Field -->
            <div class="form-group">
                {!! Form::label('status', '兼职状态:') !!}
                  <select class="form-control"  name="status">
                        <option  value="审核中" @if(!empty($project)) {!! $project->status=='审核中'?'selected':'' !!} @endif>审核中</option>
                        <option  value="通过" @if(!empty($project)) {!! $project->status=='通过'?'selected':'' !!} @endif>通过</option>
                        <option  value="不通过" @if(!empty($project)) {!! $project->status=='不通过'?'selected':'' !!} @endif>不通过</option>
                   </select>
            </div>

            <div class="form-group">
                {!! Form::label('pay_status', '付款状态:') !!}
                   <select class="form-control"  name="pay_status">
                        <option  value="待付款" @if(!empty($project)) {!! $project->pay_status=='待付款'?'selected':'' !!} @endif>待付款</option>
                        <option  value="已付款" @if(!empty($project)) {!! $project->pay_status=='已付款'?'selected':'' !!} @endif>已付款</option>
                   </select>
            </div>
        </div>
    </div>

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">兼职要求</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
                 <!-- Auth Status Field -->
            
            <div class="form-group">
                {!! Form::label('rec_num', '招聘人数:') !!}
                {!! Form::text('rec_num', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('sex_need', '性别要求:') !!}
                   <select class="form-control"  name="sex_need">
                    <option  value="男" @if(!empty($project)) {!! $project->sex_need=='男'?'selected':'' !!} @endif>男</option>
                    <option  value="女" @if(!empty($project)) {!! $project->sex_need=='女'?'selected':'' !!} @endif>女</option>
                    <option  value="不限" @if(!empty($project)) {!! $project->sex_need=='不限'?'selected':'' !!} @endif>不限</option>
                   </select>
            </div>


            <div class="form-group">
                {!! Form::label('time_type', '结算周期:') !!}
                   <select class="form-control"  name="time_type">
                    <option  value="日" @if(!empty($project)) {!! $project->time_type=='日'?'selected':'' !!} @endif>日结</option>
                    <option  value="周" @if(!empty($project)) {!! $project->time_type=='周'?'selected':'' !!} @endif>周结</option>
                    <option  value="月" @if(!empty($project)) {!! $project->time_type=='月'?'selected':'' !!} @endif>月结</option>
                   </select>
            </div>

            <div class="form-group">
                {!! Form::label('length_type', '时间类型:') !!}
                   <select class="form-control"  name="length_type">
                    <option  value="短期兼职" @if(!empty($project)) {!! $project->length_type=='短期兼职'?'selected':'' !!} @endif>短期兼职</option>
                    <option  value="中期兼职" @if(!empty($project)) {!! $project->length_type=='中期兼职'?'selected':'' !!} @endif>中期兼职</option>
                    <option  value="长期兼职" @if(!empty($project)) {!! $project->length_type=='长期兼职'?'selected':'' !!} @endif>长期兼职</option>
                    <option  value="实习" @if(!empty($project)) {!! $project->length_type=='实习'?'selected':'' !!} @endif>实习</option>
                   </select>
            </div>

        </div>
    </div>

    <div class="box box-solid">
         <div class="box-header with-border">
            <h3 class="box-title">其他设置</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <!-- Mobile Field -->
            <div class="form-group">
                {!! Form::label('mobile', '电话:') !!}
                {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
            </div>

            <!-- Weixin Field -->
            <div class="form-group">
                {!! Form::label('weixin', '微信或QQ:') !!}
                {!! Form::text('weixin', null, ['class' => 'form-control']) !!}
            </div>

            <!-- View Field -->
            <div class="form-group">
                {!! Form::label('view', '浏览量:') !!}
                {!! Form::number('view', null, ['class' => 'form-control']) !!}
            </div>

        </div>
    </div>
</div>
