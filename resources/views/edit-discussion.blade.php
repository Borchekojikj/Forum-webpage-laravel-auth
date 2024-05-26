@extends('layouts.app')


@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-8">
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('discussions.update', ['id' => $discussion->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $discussion->title) }}">
                </div>
                @error('title')
                <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo">
                    @if($discussion->photo)
                    <div class="mt-2">
                        <label for="keep_photo" class="form-check-label">Keep existing photo:</label>
                        <input type="checkbox" class="form-check-input" id="keep_photo" name="keep_photo" value="1">
                    </div>
                    @endif
                </div>
                @error('photo')
                <p class=" alert alert-danger">{{ $message }}</p>
                @enderror
                <div class="mb-3">
                    <label for="desc" class="form-label">Description</label>
                    <textarea class="form-control" id="desc" rows="3" name="desc">{{ old('desc', $discussion->desc) }}</textarea>
                </div>
                @error('desc')
                <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" name="category_id">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $discussion->category_id == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                        @endforeach

                    </select>
                </div>
                @error('category')
                <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('home') }}" class="btn btn-primary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection