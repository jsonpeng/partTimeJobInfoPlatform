@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Errand Task
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('errand_tasks.show_fields')
                    <a href="{!! route('errandTasks.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
