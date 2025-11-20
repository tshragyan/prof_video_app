@extends('admin.layouts.layout')

@section('title', 'Create Plan')

@section('content')

    <form action="{{ route('admin.plans.store') }}" class="mt-5" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description" value="{{old('description')}}">
        </div>
        <div class="mb-3">
            <label for="max_video_count" class="form-label">Max Video Count</label>
            <input type="number" class="form-control" id="max_video_count" name="max_video_count" value="{{old('max_video_count')}}">
        </div>
        <div class="mb-3">
            <label for="max_video_size" class="form-label">Max Video Size</label>
            <input type="number" class="form-control" id="max_video_size" name="max_video_size" value="{{old('max_video_size')}}">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{old('price')}}">
        </div>
        <div class="mb-3">
            <label for="old_price" class="form-label">Old Price</label>
            <input type="number" class="form-control" id="old_price" name="old_price" value="{{old('old_price')}}">
        </div>
        <div class="mb-3">
            <label for="annual_price" class="form-label">Annual Price</label>
            <input type="number" class="form-control" id="annual_price" name="annual_price" value="{{old('annual_price')}}">
        </div>
        <div class="mb-3">
            <label for="old_annual_price" class="form-label">Old Annual Price</label>
            <input type="number" class="form-control" id="old_annual_price" name="old_annual_price" value="{{old('old_annual_price')}}">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
