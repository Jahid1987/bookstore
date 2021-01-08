@extends('admin.app')

@section('links')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">Categories</li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<h2>Create Category</h2>
<form action="{{route('admin.category.update', $category)}}" method="POST" accept-charset="utf-8">
	@csrf
	@method('put')

	<div class="form-group row">
		<div class="col-md-12">
			@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
		</div>
		<div class="col-md-12">
			@if (session()->has('message'))
			<div class="alert alert-success">
				<ul>
					{{session('message')}}
				</ul>
			</div>
			@endif
		</div>
		<div class="col-sm-12">
			<label class="form-control-label">Title: </label>
			<input type="text" id="txturl" name="title" class="form-control " value="{{@$category->title}}">
		</div>
	</div>
	<div class="form-group row">
		<div class="col-md-12">
			<label for="" class="form-control-label">Description: </label>
			<textarea name="description" id="editor" class="form-control" rows="10" cols="80" height="300px" >{!! $category->description !!}</textarea>
		</div>
	</div>
	@php
		$ids = (isset($category->parents) && $category->parents->count() > 0 ) ? array_pluck($category->parents, 'id') : null
	@endphp


	<div class="form-group row">
		<div class="col-sm-12">
			<label class="form-control-label">Select Category: </label>
			<select name="parent_id[]" id="parent_id" class="form-control js-example-basic-multiple" multiple>
				@if(isset($categories))
				<option value="0">Top Level</option>
				@foreach($categories as $cat)
				<option value="{{$cat->id}}" @if(!is_null($ids) && in_array($cat->id, $ids)) {{'selected'}} @endif>{{$cat->title}}</option>
				@endforeach
				@endif
			</select>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-md-6">
			<input type="submit" name="submit" class="btn btn-primary btn-sm" value="Add Category">
		</div>
	</div>
</form>
@endsection
@section('scripts')
<!-- Linking select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- linking ckeditor4-->
<script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script>
	// select2
	$(document).ready(function() {
	$('.js-example-basic-multiple').select2();
	});
	// ckeditor4
	CKEDITOR.replace( 'editor' );
		//Slug creating
	// $('#txturl').on('keyup', function(){
	// var url = slugify($(this).val());
	// $('#url').html(url);
	// $('#slug').val(url);
	// })
</script>
@endsection