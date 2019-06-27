@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Refund Log
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($refundLog, ['route' => ['refundLogs.update', $refundLog->id], 'method' => 'patch']) !!}

                        @include('refund_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection