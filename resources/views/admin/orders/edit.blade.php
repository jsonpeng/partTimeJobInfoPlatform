@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            编辑订单
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($order, ['route' => ['orders.update', $order->id], 'method' => 'patch']) !!}

                        @include('admin.orders.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection