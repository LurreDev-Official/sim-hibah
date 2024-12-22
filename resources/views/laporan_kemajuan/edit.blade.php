@extends('layouts.main_layout')
@section('content')


<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif


@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">kelola-event
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <!--end::Separator-->
                <!--begin::Description-->
                <small class="text-muted fs-7 fw-bold my-1 ms-1">Edit</small>
                <!--end::Description--></h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
  <!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6 mb-7">
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <!--begin::Add user-->
                        <a class="btn btn-primary" href="{{ route('kelola-event.index') }}"> Back</a>

                    </div>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->

                {!! Form::model($event, ['method' => 'PATCH', 'files'=> true ,'route' => ['kelola-event.update', $event->id]]) !!}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                        <div class="form-group">
                            <strong>nama_event:</strong>
                            {!! Form::text('nama_event', null, array('value' => '{{$event->nama_event}}','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleTextarea">Keterangan</label>
                        <textarea name="keterangan" class="form-control form-control-solid" rows="3">{{$event->nama_event}}</textarea>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                        <div class="form-group">
                            <strong>Flayer max(1mb) : <a href="{{ asset($event->file_img)}}" target="_blank" rel="noopener noreferrer">Lihat file</a></strong>
                            <input type="file" name="file_img" class="form-control form-control-lg form-control-solid"/> 
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                        <div class="form-group">
                            <strong>Background Email : max(1mb)<a href="{{ asset($event->file_bgemail)}}" target="_blank" rel="noopener noreferrer">Lihat file</a></strong>
                            <input type="file" name="file_bgemail" class="form-control form-control-lg form-control-solid"/> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <strong>Pilih Penyelengara:</strong>
                            <select class="form-select form-select-solid" name="user_id"  >
                                <option value="{{ $event->user_id}}"> {{ $event->users->name}} </option>
                                <option value="">Pilih </option>
                                @foreach ($users as $item)
                                <option value="{{ $item->id}}"> {{ $item->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end pt-7">
                        <button type="submit" class="btn btn-sm fw-bolder btn-primary">Update</button>
                    </div> 
                </div>
                {!! Form::close() !!}

                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
</div>
<!--end::Post-->
@endsection
