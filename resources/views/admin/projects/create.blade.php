@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            添加兼职
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
    
                <div class="row">
                    {!! Form::open(['route' => 'projects.store']) !!}

                        @include('admin.projects.fields')

                    {!! Form::close() !!}
                </div>
     
    </div>
     @include('admin.partials.imagemodel')
     @include('layouts.js')
@endsection

@include('admin.projects.js')

