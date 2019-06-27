@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            编辑企业
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
     {{--   <div class="box box-primary">
           <div class="box-body"> --}}
               <div class="row">
                   {!! Form::model($caompany, ['route' => ['caompanies.update', $caompany->id], 'method' => 'patch']) !!}

                        @include('admin.caompanies.fields')

                   {!! Form::close() !!}
               </div>
         {{--   </div>
       </div> --}}
   </div>
    @include('admin.partials.imagemodel')
    @include('layouts.js')
@endsection


@include('admin.caompanies.js')