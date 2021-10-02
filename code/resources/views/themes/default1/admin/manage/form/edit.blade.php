@extends('themes.default1.layouts.blank')
@section('Manage')
class="active"
@stop
@section('manage-bar')
active
@stop
@section('form')
class="active"
@stop
@section('HeadInclude')
@stop
@section('PageHeader')
@stop
@section('breadcrumbs')
<ol class="breadcrumb">
</ol>
@stop
@section('content')
{!! Form::model($forms,['url' => 'form/'.$forms->id,'method' => 'PATCH']) !!}
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
               <div class="box-body">
                  <div class="box-header">
                   <h2 class="box-title"style="margin-left:-10px">{{Lang::get('lang.create')}}</h2>{!! Form::submit(Lang::get('lang.save'),['class'=>'pull-right btn btn-primary'])!!}</div>
		 <div class="box-body table-responsive no-padding"style="overflow:hidden">
		 <div class="row">
		 <div class="col-md-6">
		<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
			{!! Form::label('title',Lang::get('lang.title')) !!}
			{!! $errors->first('title', '<spam class="help-block">:message</spam>') !!}
			{!! Form::text('title',null,['class' => 'form-control']) !!}
			</div>
		</div>
		 <div class="col-md-6">
		<div class="form-group {{ $errors->has('label') ? 'has-error' : '' }}">
		{!! Form::label('label',Lang::get('lang.label')) !!}
		{!! $errors->first('label', '<spam class="help-block">:message</spam>') !!}
		{!! Form::text('label',null,['class' => 'form-control']) !!}
        </div>
        </div>
                  <div class="col-md-4">
		           <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
		           {!! Form::label('type',Lang::get('lang.type')) !!}
		           {!! $errors->first('type', '<spam class="help-block">:message</spam>') !!}
                   {!!Form::select('type', [''=>'Select a Type','types'=>$type->lists('type','id')] ,null,['class' => 'form-control'] ) !!}
                </div>
                </div>
                <div class="col-md-4">
		           <div class="form-group {{ $errors->has('visibility') ? 'has-error' : '' }}">
		           {!! Form::label('visibility',Lang::get('lang.visibility')) !!}
		           {!! $errors->first('visibility', '<spam class="help-block">:message</spam>') !!}
		           {!!Form::select('visibility', [''=>'Select a Visibility','visibilities' =>$visibility->lists('visibility','id')],null,['class' => 'form-control'] ) !!}
		         </div>
		         </div>
		          <div class="col-md-4">
		           <div class="form-group">
		           {!! Form::label('variable',Lang::get('lang.variable')) !!}
		           {!! Form::text('variable',null,['class' => 'form-control']) !!}
		           </div>
		           </div>
		<div class="col-md-6">
		<div class="form-group">
			{!! Form::label('instruction',Lang::get('lang.instruction')) !!}
			{!! Form::textarea('instruction',null,['class' => 'form-control','size' => '10x5']) !!}
		</div>
		</div>
	<div class="col-md-6">
	<div class="form-group">
		{!! Form::label('internal_notes',Lang::get('lang.internal_notes')) !!}
		{!! Form::textarea('internal_notes',null,['class' => 'form-control','size' => '10x5']) !!}
	</div>
	</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
	@stop
</div>
@section('FooterInclude')
@stop
@stop
