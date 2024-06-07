<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == 'admin') {
            $news = News::all();
        } else {
            $news = $request->user()->news;
        }
        return response()->json($news);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $news = $request->user()->news()->create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => 'pending',
        ]);

        return response()->json($news, 201);
    }

    public function show($id)
    {
        $news = News::findOrFail($id);
        return response()->json($news);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        if ($request->user()->role != 'admin' && $news->user_id != $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $news->update($request->all());
        return response()->json($news);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);

        if ($request->user()->role != 'admin' && $news->user_id != $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $news->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }

    public function moderate(Request $request, $id)
    {
        if ($request->user()->role != 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $news = News::findOrFail($id);
        $news->update(['status' => $request->status]);

        return response()->json($news);
    }
}

