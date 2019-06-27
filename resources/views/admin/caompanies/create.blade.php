@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            添加企业
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
     {{--    <div class="box box-primary">

            <div class="box-body"> --}}
                <div class="row">
                    {!! Form::open(['route' => 'caompanies.store']) !!}

                        @include('admin.caompanies.fields')

                    {!! Form::close() !!}
                </div>
      {{--       </div>
        </div> --}}
    </div>
     @include('admin.partials.imagemodel')
     @include('layouts.js')
@endsection


@include('admin.caompanies.js')

