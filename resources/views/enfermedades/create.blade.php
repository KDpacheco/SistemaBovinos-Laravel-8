@section('title')
SGB - Enfermedades
@endsection
@extends('layouts.main')
@section('style')
<!-- Range Slider css -->
<link href="{{ asset('assets/plugins/ion-rangeSlider/ion.rangeSlider.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('rightbar-content')
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <div class="breadcrumb-list">
                <ol class="breadcrumb">


                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbbar -->
<!-- Start col -->
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <h5 class="card-title">Enfermedades</h5>
        </div>
        @if (count($errors)>0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error )
                <li>
                    {{ $error }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        {!! Form::open(array('url'=>'enfermedades','method'=>'POST','autocomplete'=>'off')) !!}
        {{ Form::token() }}
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label>Código del animal</label>
                    <select name="animal" class="form-control selectpicker" data-live-search="true"
                        data-toggle="tooltip" data-placement="top" title="Seleccione Código del animal">
                        <option value="" disabled="" selected="">Seleccione Código: </option>
                        @foreach ($animales as $r)
                        @if (old('animal')==$r->animal_id )
                        <option selected value="{{ $r->animal_id }}">{{ $r->animal_id}}</option>
                        @else
                        <option value="{{ $r->animal_id }}">{{ $r->animal_id}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label>Enfermedad</label>
                    <select id="Enfermedad" name="enfermedad" class="form-control selectpicker" data-live-search="true"
                        data-toggle="tooltip" data-placement="top" title="Seleccione enfermedad">
                        <option value="" disabled="" selected="">Seleccione Enfermedad: </option>
                        @foreach ($listado as $r)
                        @if (old('enfermedad')==$r->enfermedades_id )
                        <option selected value="{{ $r->enfermedades_id }}">{{ $r->enfermedades_nombre}}</option>
                        @else
                        <option value="{{ $r->enfermedades_id }}">{{ $r->enfermedades_nombre}}</option>
                        @endif
                        @endforeach
                        <option value="nueva" @if (old('enfermedad')=="nueva" ) {{ 'selected ' }} @endif> nueva enfermedad</option>
                    </select>
                </div>
            </div>
            <div id="nueva_enfermedad" class="col-lg-6 col-sm-6 col-md-6 col-xs-12" style="display:none;">
                <div class="form-group">
                    <label>Nombre de la Enfermedad</label>
                    <input type="text" name="enfermedades_nombre" value="{{ old('enfermedades_nombre') }}"
                        class="form-control" data-toggle="tooltip" data-placement="top" placeholder="nombre"
                        title="Ingrese nombre de la enfermedad">
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label>Fecha de Detección</label>
                    <input type="date" name="fecha" value="{{ old('fecha') }}" class="form-control"
                        data-toggle="tooltip" data-placement="top" title="Seleccione fecha de detección">
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label>Estado de la enfermedad</label>
                    <select id="Estado" name="estado" class="form-control" data-toggle="tooltip" data-placement="top"
                        title="Seleccione Código del animal">
                        <option value="" disabled="" selected="">Seleccione: </option>
                        <option value="Tratado" @if (old('estado')=="Tratado" ) {{ 'selected ' }} @endif>Tratado
                        </option>
                        <option value="No Tratado" @if (old('estado')=="No Tratado" ) {{ 'selected ' }} @endif>No
                            Tratado </option>
                    </select>
                </div>
            </div>

            <div id="Fecha2" style="display:none;" class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label>Fecha de Tratamiento</label>
                    <input type="date" name="fecha_tratamiento" value="{{ old('fecha_tratamiento') }}"
                        class="form-control" data-toggle="tooltip" data-placement="top"
                        title="Seleccione Fecha de tratamiento">
                </div>
            </div>
            <div id="tratamiento" style="display:none;" class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="form-group">
                    <label>Ingrese el tratamiento</label>
                    <input type="text" name="tratamiento" value="{{ old('tratamiento') }}" class="form-control"
                        data-toggle="tooltip" data-placement="top" placeholder="tratamiento"
                        title="Ingrese tratamiento">
                </div>
            </div>

        </div>
        <div>
            <p class="text-center">
                <button type="reset" class="btn btn-info" style="margin-right: 20px;"><i class="zmdi zmdi-roller"></i>
                    &nbsp;&nbsp; Limpiar</button>
                <button type="submit" class="btn btn-primary"><i class="zmdi zmdi-floppy"></i> &nbsp;&nbsp;
                    Guardar</button>
            </p>
        </div>

        {!! Form::close() !!}
    </div>
</div>
@endsection