<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    /**
     * Display all topics or filter by subject
     */
    public function index(Request $request)
    {
        $query = Topic::with('subject')->withCount('questions');

        if ($request->has('subject_id')) {
            $query->where('topic_subject_id', $request->subject_id);
        }

        $topics = $query->get();

        return response()->json([
            'success' => true,
            'data' => $topics
        ]);
    }

    /**
     * Store a newly created topic
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic_name' => 'required|string|max:100',
            'topic_subject_id' => 'required|exists:subjects,subject_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $topic = Topic::create($request->only(['topic_name', 'topic_subject_id']));

        return response()->json([
            'success' => true,
            'data' => $topic->load('subject'),
            'message' => 'Topic created successfully'
        ], 201);
    }

    /**
     * Display the specified topic with questions
     */
    public function show(string $id)
    {
        $topic = Topic::with(['subject', 'questions.answers'])
            ->withCount('questions')
            ->find($id);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $topic
        ]);
    }

    /**
     * Update the specified topic
     */
    public function update(Request $request, string $id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'topic_name' => 'required|string|max:100',
            'topic_subject_id' => 'required|exists:subjects,subject_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $topic->update($request->only(['topic_name', 'topic_subject_id']));

        return response()->json([
            'success' => true,
            'data' => $topic->load('subject'),
            'message' => 'Topic updated successfully'
        ]);
    }

    /**
     * Remove the specified topic
     */
    public function destroy(string $id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found'
            ], 404);
        }

        if ($topic->questions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete topic with existing questions'
            ], 400);
        }

        $topic->delete();

        return response()->json([
            'success' => true,
            'message' => 'Topic deleted successfully'
        ]);
    }
}
