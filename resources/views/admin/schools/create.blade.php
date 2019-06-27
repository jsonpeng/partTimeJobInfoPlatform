@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            添加学校
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'schools.store']) !!}

                        @include('admin.schools.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include('layouts.js')
@endsection
