@extends('layouts.app')


@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-8">


            <form action="{{ route('discussions.create') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                </div>
                @error('title')
                <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo" value="{{ old('photo') }}">
                </div>
                @error('photo')
                <p class=" alert alert-danger">{{ $message }}</p>
                @enderror
                <div class="mb-3">
                    <label for="desc" class="form-label">Description</label>
                    <textarea class="form-control" id="desc" rows="3" name="desc">{{ old('desc') }}</textarea>
                </div>
                @error('desc')
                <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" name="category_id">
                        <option value="" selected disabled> Open select</option>
                        @foreach($categories as $category)

                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                        @endforeach

                    </select>
                </div>
                @error('category_id')
                <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection