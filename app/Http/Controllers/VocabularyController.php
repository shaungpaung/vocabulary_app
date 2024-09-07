<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Http\Request;

class VocabularyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $userId = $request->user()->id;
        $query = Vocabulary::where('created_uid', $userId);

        if ($request->has('search_text')) {
            $search_text = $request->search_text;
            $query->where('title', 'LIKE', '%' . $search_text . '%')
                ->orWhere('definition', 'LIKE', '%' . $search_text . '%');
        }
        if ($request->has('is_revised')) {
            $query->where('is_revised', $request->boolean('is_revised'));
        }
        if ($request->has('page')) {
            $limit = 50;
            $vocabulary = $query->paginate($limit);
        } else {
            $vocabulary = $query->get();
        }

        return response()->json($vocabulary);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'required',
            'definition' => 'required',
            'synonyms' => 'nullable',
            'antonyms' => 'nullable',
            'type' => 'required',
            'example' => 'required',
            'is_revised' => 'required'
        ]);
        $userId = $request->user()->id;
        $validated['created_uid'] = $userId;
        $vocabulary = Vocabulary::create($validated);
        return response()->json($vocabulary);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $vocabulary = Vocabulary::find($id);
        if (!$vocabulary) {
            return response()->json(['message' => 'Could not find this id: ' . $id], 422);
        }
        return response()->json($vocabulary);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $vocabulary = Vocabulary::find($id);
        if (!$vocabulary) {
            return response()->json(['message' => 'Could not find this id: ' . $id], 422);
        }
        $validated = $request->validate([
            'title' => 'required',
            'definition' => 'required',
            'synonyms' => 'nullable',
            'antonyms' => 'nullable',
            'type' => 'required',
            'example' => 'required',
            'is_revised' => 'required'
        ]);
        $userId = $request->user()->id;
        $validated['created_uid'] = $userId;
        $vocabulary->update($validated);
        return response()->json(['message' => 'Vocabulary updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $vocabulary = Vocabulary::find($id);
        if (!$vocabulary) {
            return response()->json(['message' => 'Could not find this id: ' . $id], 422);
        }
        $vocabulary->delete();
        return response()->json(['message' => 'Vocabulary deleted successfully']);

    }
}