@extends('layouts.app')

@section('content')

<?php

use App\Models\Discussion;
use Illuminate\Support\Facades\Auth;

$approvedDiscussions = Discussion::where('approved', 1)->get();
$allDiscussions = Discussion::all();

if (Auth::user() && Auth::user()->role_id == 2) {

    $discussions = $allDiscussions;
} else {
    if (Auth::user()) {
        $user = Auth::user();

        $userDiscussions = $user->discussions;
        $discussions = $approvedDiscussions;

        $combinedDiscussions = $userDiscussions->merge($discussions);

        $discussions =  $combinedDiscussions;
    } else {
        $discussions  = $approvedDiscussions;
    }
}

?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <h1 class="mt-2 mb-4 text-center">Welcome to the Forum</h1>

            <a href="{{ route('discussions.create') }}" class="btn btn-secondary">Add new discussion</a>
            @if(Auth::user() && Auth::user()->isAdmin())
            <div class="mt-2">
                <a href="#" class="btn b    tn-primary">Aprove discussion</a>

            </div>
            @endif

            @forelse($discussions as $discussion)

            <div class="row align-items-center border m-4 ">

                <div class="col-sm-12 col-lg-4 col-md-6 mt-3 mb-3">
                    <a href="{{ route('discussions.show', ['id'=> $discussion->id]) }}">
                        <div class="p-2 text-center">
                            <img src="{{ asset('storage/images/products/' . $discussion->photo) }}" alt="Discussion Photo" width="250px" height="250px">
                        </div>
                    </a>
                </div>
                <div class="col-5 mb-3">
                    <div>{{$discussion->title}}</div>
                    <div>{{$discussion->desc}}</div>
                </div>
                <div class="col-lg-3 col-12  text-center">
                    <div class="d-flex align-items-center justify-content-center">

                        @if((Auth::user() && Auth::user()->isAdmin()) || (Auth::user() && $discussion->user_id == Auth::user()->id))
                        <span class="fs-1 me-2"><a href="{{ route('discussions.edit', ['id' => $discussion->id]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit discussion"><i class="fas fa-edit"></i></a></span>
                        <span class="fs-1 me-2"><a href="{{ route('discussions.delete', ['id' => $discussion->id]) }}"><i class="fas fa-trash-alt" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete discussion"></i></a></span>

                        @if(Auth::user() && Auth::user()->isAdmin())
                        <span class="fs-1 me-2"> <a href="{{ route('discussions.approve', ['id' => $discussion->id]) }}"><i @class([ 'fas' , 'fa-check-circle' , 'text-warning'=> !$discussion->approved,])
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $discussion->approved ? 'Approved' : 'Approve discussion' }}"
                                    ></i></a></span>
                        <span class="fs-5">
                            @else
                            <span class="fs-1 me-2"> <i @class([ 'fas' , 'fa-check-circle' , 'text-warning'=> !$discussion->approved, 'text-success'=> $discussion->approved,])
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $discussion->approved ? 'Approved' : 'Waitung for Approval' }}"
                                    ></i></a></span>
                            <span class="fs-5">
                                @endif

                                {{ $discussion->category->title}} | {{ $discussion->user->name}}
                            </span>

                            @endif
                    </div>

                </div>
            </div>
            @empty
            <p class=" mt-5 h1 text-secondary mb-4 text-center">Nothing here yet! Start a topic!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection