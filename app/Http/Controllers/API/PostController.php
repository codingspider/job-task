<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends BaseController
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        try {
            $posts = Post::all();
            $success['posts'] =  $posts;
            return $this->sendResponse($success, 'Post retrived successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Server error.', ['error'=>$e->getMessage()]);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        DB::beginTransaction();
        try {
            $input = $request->all();
            $post = Post::create($input);
            $success['post'] =  $post;
            DB::commit();
            return $this->sendResponse($success, 'Post created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Server error.', ['error'=>$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     * Fetches the post from the database by its id
     */
    public function show($id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return $this->sendError('Data Not found', ['error'=> 'Post not found']);
            }
            $success['post'] =  $post;
            return $this->sendResponse($success, 'Post retrived successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Server error', ['error'=> $e->getMessage()]);
        }
    }

   
}
