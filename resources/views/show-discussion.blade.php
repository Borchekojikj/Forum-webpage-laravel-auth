@extends('layouts.app')


@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="row mt-4">
                <div class="col-6">
                    <img src="{{ asset('storage/images/products/' . $discussion->photo) }}" alt="" width="100%" height="100%">
                </div>
                <div class="col-6">
                    <h1 class="text-center h4 mt-3 mb-5">{{$discussion->title }}</h1>
                    <p class="text-start h5 mt-3 mb-5"> {{$discussion->desc }}</p>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 mt-2 mb-5">
                    <h1>Comments</h1>
                    @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="row">
                        @foreach($comments as $comment)
                        <div class="row mt-1 mx-1 border align-items-center py-2">
                            <div class="col-3 ">
                                <p class="fw-bold mb-0">{{$comment->user->name}}</p>
                            </div>
                            <div class="col-6 ">
                                <p class="mb-0">{{$comment->comment}}</p>
                            </div>


                            @if(Auth::user() && Auth::user()->id == $comment->user->id)
                            <div class="col-3">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">{{$comment->created_at}}</p>
                                    <a href="{{ route('delete.comment', ['id'=>$comment->id]) }}" class="btn btn-danger ms-5">Delete</a>
                                </div>

                            </div>
                            @else
                            <div class="col-3">
                                <p class="mb-0">{{$comment->created_at}}</p>
                            </div>
                            @endif
                        </div>

                        @endforeach
                    </div>
                    <form action="{{ Route('post.comment', ['id' =>$discussion->id]) }}" method="post">
                        @csrf
                        @auth

                        <div class="mb-3 mt-3">
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="comment" placeholder="Write your comment here..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark">Post</button>
                        @else
                        <div class="mb-3 mt-3">
                            <p>Login in to write an comment...</p>
                            <a href="{{ route('login') }}" class="btn btn-dark">Login</a>
                        </div>
                        @endauth
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>



@endsection