@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {!! $school->name !!}
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.schools.show_fields')
                    <a href="{!! route('schools.index') !!}" class="btn btn-default">返回</a>
                </div>
            </div>
        </div>
    </div>
@endsection
