<div class="form-group col-sm-8">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">跑腿任务详情</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <!-- Name Field -->
            <div class="form-group">
                {!! Form::label('name', '任务名称:') !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>

            <!-- User Id Field -->
                {!! Form::hidden('user_id', null, ['class' => 'form-control']) !!}

            <!-- Tem Id Field -->
                {!! Form::hidden('tem_id', null, ['class' => 'form-control']) !!}


            <!-- Remark Field -->
            <div class="form-group ">
                {!! Form::label('remark', '备注:') !!}
                {!! Form::textarea('remark', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">

                      <section class="content-header" style="height: 50px; padding: 0; padding-top: 15px;">
                      <h1 class="pull-left" style="font-size: 14px; font-weight: bold; line-height: 34px;padding-bottom: 0px;">备注展示图片</h1>

                       <h3 class="pull-right" style="margin: 0">
                                <input type="hidden" name="addimage" value="0" id="errand_image">
                                <div class="pull-right" style="margin: 0">
                                    <a data-toggle="modal" href="javascript:;" data-target="#myModal" class="btn btn-primary" type="button" onclick="errandImage('errand_image')">添加备注展示图片</a>
                                </div>
                        </h3>
                    </section>

                    <div class="images" style="display:@if(count($images)) block @else none @endif;">
                            <?php $i=0;?>
                            @foreach ($images as $image)
                            <div class="image-item" id="errand_image_{{ $i }}">
                                <img src="{!! $image->
                                url !!}" alt="" style="max-width: 100%;">
                                <div class="tr">
                                    <div class="btn btn-danger btn-xs" onclick="deletePic({{ $i }})">删除</div>
                                </div>
                                 <input type='hidden' name='images[]' value='{!! $image->
                                url !!}'>
                            </div>
                            <?php $i++;?>
                            @endforeach
                    </div>

            </div>

            <!-- Give Price Field -->
            <div class="form-group">
                {!! Form::label('give_price', '打赏金额:') !!}
                {!! Form::text('give_price', null, ['class' => 'form-control']) !!}
            </div>

            <!-- Price Type Field -->
            <div class="form-group">
                {!! Form::label('price_type', '物品金额类型:') !!}
                {!! Form::text('price_type', null, ['class' => 'form-control']) !!}
            </div>

            <!-- Item Cost Field -->
            <div class="form-group">
                {!! Form::label('item_cost', '物品费用:') !!}
                {!! Form::text('item_cost', null, ['class' => 'form-control']) !!}
            </div>

     
            {!! Form::hidden('remain_time', null, ['class' => 'form-control']) !!}
          

            <div class="row">
                    <!-- Wish Time Hour Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('remain_time_hour', '剩余时间(小时):') !!}
                        {!! Form::text('remain_time_hour', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Wish Time Minute Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('remain_time_min', '剩余时间(分钟):') !!}
                        {!! Form::text('remain_time_min', null, ['class' => 'form-control']) !!}
                    </div>
            </div>

            <div class="row">
                    <!-- Wish Time Hour Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('wish_time_hour', '希望送达时间(小时):') !!}
                        {!! Form::text('wish_time_hour', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Wish Time Minute Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('wish_time_minute', '希望送达时间(分钟):') !!}
                        {!! Form::text('wish_time_minute', null, ['class' => 'form-control']) !!}
                    </div>
            </div>

            <!-- Mobile Field -->
            <div class="form-group">
                {!! Form::label('mobile', '发布人手机号:') !!}
                {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
            </div>

            <!-- School Name Field -->
            <div class="form-group">
                {!! Form::label('school_name', '发布学校:') !!}
                {!! Form::text('school_name', null, ['class' => 'form-control','readonly'=>'readonly']) !!}
            </div>
        </div>
    </div>
</div>


<div class="form-group col-sm-4">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">管理设置</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('errandTasks.index') !!}" class="btn btn-default">返回</a>
            <!-- Status Field -->
            <div class="form-group">
                {!! Form::label('status', '发布者状态:') !!}

                <select name="status" class="form-control">
                    <option value="已发布" @if(!empty($errandTask) && $errandTask->status=='已发布') selected="selected" @endif>已发布</option>
                    <option value="待收货" @if(!empty($errandTask) && $errandTask->status=='待收货') selected="selected" @endif>待收货</option>
                    <option value="已收货" @if(!empty($errandTask) && $errandTask->status=='已收货') selected="selected" @endif>已收货</option>
                    <option value="已取消" @if(!empty($errandTask) && $errandTask->status=='已取消') selected="selected" @endif>已取消</option>
                </select>
            </div>
            <div class="form-group">
                {!! Form::label('pay_status', '支付状态:') !!}

                <select name="pay_status" class="form-control">
                    <option value="未支付" @if(!empty($errandTask) && $errandTask->pay_status=='未支付') selected="selected" @endif>未支付</option>
                    <option value="已支付" @if(!empty($errandTask) && $errandTask->pay_status=='已支付') selected="selected" @endif>已支付</option>
                </select>
            </div>
        </div>
    </div>

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">买手设置</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {!! Form::label('errand_status', '买手状态:') !!}

                <select name="errand_status" class="form-control">
                    <option value="待送达" @if(!empty($errandTask) && $errandTask->errand_status=='待送达') selected="selected" @endif>待送达</option>
                    <option value="确认送达" @if(!empty($errandTask) && $errandTask->errand_status=='确认送达') selected="selected" @endif>确认送达</option>
                    <option value="已收款" @if(!empty($errandTask) && $errandTask->errand_status=='已收款') selected="selected" @endif>已收款</option>
                </select>
            </div>
            <!-- Wait Buyer Enter Field -->
            <div class="form-group">
                {!! Form::label('wait_buyer_enter', '等待买手确认:') !!}

                <select name="wait_buyer_enter" class="form-control">
                    <option value="0" @if(!empty($errandTask) && $errandTask->wait_buyer_enter==0) selected="selected" @endif>否</option>
                    <option value="1" @if(!empty($errandTask) && $errandTask->wait_buyer_enter==1) selected="selected" @endif>是</option>
                </select>
            </div>

            <!-- Tem Word1 Field -->
            <div class="form-group">
                {!! Form::label('tem_word1', '模板关键字1:') !!}
                {!! Form::text('tem_word1', null, ['class' => 'form-control']) !!}
            </div>

            <!-- Tem Word2 Field -->
            <div class="form-group">
                {!! Form::label('tem_word2', '模板关键字2:') !!}
                {!! Form::text('tem_word2', null, ['class' => 'form-control']) !!}
            </div>

        </div>
    </div>


    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">位置设置</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <!-- Province Field -->
                <div class="form-group">
                    {!! Form::label('province', '省份:') !!}
                    {!! Form::text('province', null, ['class' => 'form-control']) !!}
                </div>

                <!-- City Field -->
                <div class="form-group">
                    {!! Form::label('city', '城市:') !!}
                    {!! Form::text('city', null, ['class' => 'form-control']) !!}
                </div>

                <!-- District Field -->
                <div class="form-group">
                    {!! Form::label('district', '区域:') !!}
                    {!! Form::text('district', null, ['class' => 'form-control']) !!}
                </div>

                <!-- Address Field -->
                <div class="form-group">
                    {!! Form::label('address', '发布地址:') !!}
                    {!! Form::text('address', null, ['class' => 'form-control']) !!}
                </div>

                <!-- Lat Field -->
                <div class="form-group">
                    {!! Form::label('lat', '纬度:') !!}
                    {!! Form::text('lat', null, ['class' => 'form-control']) !!}
                </div>

                <!-- Lon Field -->
                <div class="form-group">
                    {!! Form::label('lon', '经度:') !!}
                    {!! Form::text('lon', null, ['class' => 'form-control']) !!}
                </div>
        </div>

    </div>

</div>
