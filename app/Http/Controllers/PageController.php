<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDiscussionRequest;
use App\Models\Category;
use App\Models\Discussion;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isFalse;

class PageController extends Controller
{



    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', "You need to be logged in to do that!");
        }

        $categories = Category::all();

        return view('create-discussion', compact('categories'));
    }

    public function store(CreateDiscussionRequest $request)
    {

        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        if ($request->hasFile('photo')) {

            $image = $request->file('photo');
            $image_name = uniqid() . '.' . $image->getClientOriginalExtension();
            $destination_path = public_path('storage/images/products');
            $image->move($destination_path, $image_name);
            $data['photo'] = $image_name;
        }


        $discussion = new Discussion($data);
        $discussion->save();

        return redirect()->route('home')->with('status', 'Discussion created and is waiting for approval.');
    }

    public function show(string $id)
    {
        $discussion = Discussion::find($id);
        $comments = $discussion->comments;
        return view('show-discussion', compact('discussion', 'comments'));
    }

    public function edit(string $id)
    {
        $discussion = Discussion::findOrFail($id);
        $discussionUser = $discussion->user;
        if (Auth::user()->role_id == 2) {
            $access =   true;
        } else if ($discussion->user_id ==  Auth::user()->id) {
            $access =   true;
        } else {
            $access =   false;
        }




        if ($access) {
            $categories = Category::all();
            $discussion = Discussion::find($id);
            return view('edit-discussion', compact('categories', 'discussion'));
        } else {
            return redirect()->back()->with('error', 'Access denied');
        }
    }

    public function update(Request $request, string $id)
    {
        // Find the discussion to be updated
        $discussion = Discussion::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string',
            'photo' => 'unique:discussions,photo',
            'desc' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);


        $discussion->title = $validatedData['title'];
        $discussion->desc = $validatedData['desc'];
        $discussion->category_id = $validatedData['category_id'];
        if (!$request->keep_photo && !$request->hasFile('photo')) {
            return redirect()->back()->with('error', 'You need to upload a new photo or check the "Keep Photo" checkbox.');
        }

        if (!$request->keep_photo) {
            if ($request->hasFile('photo')) {
                // Delete the old photo
                try {
                    if ($discussion->photo) {
                        $filePath = public_path("storage/images/products/{$discussion->photo}");
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                } catch (\Exception $e) {
                    // Log or handle the exception
                    return back()->with('error', 'Failed to delete photo: ' . $e->getMessage());
                }

                // Save new Photo to Public
                $image = $validatedData['photo'];
                $image_name = uniqid() . '.' .  $validatedData['photo']->getClientOriginalExtension();
                $destination_path = public_path('storage/images/products');
                $image->move($destination_path, $image_name);
                $discussion->photo = $image_name;
            }
        }

        // After update, set status to not approved
        $discussion->approved = 0;
        // Save updated discussion
        $discussion->save();

        return redirect()->route('home')->with('status', 'Discussion updated successfully, and is waitung for approval.');
    }

    /**
     */
    public function destroy(string $id)
    {
        $discussion = Discussion::findOrFail($id);
        try {
            if ($discussion->photo) {
                $filePath = public_path("storage/images/products/{$discussion->photo}");
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        } catch (\Exception $e) {
            // Log or handle the exception
            return back()->with('error', 'Failed to delete photo: ' . $e->getMessage());
        }

        // Delete the discussion
        $discussion->delete();

        return redirect()->back()->with('status', 'Discussion deleted successfully.');
    }

    public function approve(string $id)
    {
        $desc = Discussion::find($id);
        $desc->approved = 1;
        $desc->save();
        return redirect()->back()->with('status', 'Discussion has been approved successfully.');
    }
}
