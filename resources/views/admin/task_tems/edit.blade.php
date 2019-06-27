@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            编辑任务模板描述
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($taskTem, ['route' => ['taskTems.update', $taskTem->id], 'method' => 'patch']) !!}

                        @include('admin.task_tems.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
  @include('layouts.js')
@endsection

