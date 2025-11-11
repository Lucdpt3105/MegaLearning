<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Display all questions or filter by topic/difficulty
     */
    public function index(Request $request)
    {
        $query = Question::with('topic')->withCount('answers');

        if ($request->has('topic_id')) {
            $query->where('question_topic_id', $request->topic_id);
        }

        if ($request->has('difficulty_id')) {
            $query->where('question_difficulty_id', $request->difficulty_id);
        }

        if ($request->has('type_id')) {
            $query->where('question_type_id', $request->type_id);
        }

        $questions = $query->get();

        return response()->json([
            'success' => true,
            'data' => $questions
        ]);
    }

    /**
     * Store a newly created question with answers
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_topic_id' => 'required|exists:topics,topic_id',
            'question_type_id' => 'required|integer',
            'question_difficulty_id' => 'required|integer',
            'question_score' => 'nullable|numeric',
            'answers' => 'required|array|min:2',
            'answers.*.answer_text' => 'required|string',
            'answers.*.answer_is_correct' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $question = Question::create($request->only([
            'question_text',
            'question_topic_id',
            'question_type_id',
            'question_difficulty_id',
            'question_score'
        ]));

        // Create answers for the question
        foreach ($request->answers as $answerData) {
            $question->answers()->create($answerData);
        }

        return response()->json([
            'success' => true,
            'data' => $question->load(['topic', 'answers']),
            'message' => 'Question created successfully'
        ], 201);
    }

    /**
     * Display the specified question with answers
     */
    public function show(string $id)
    {
        $question = Question::with(['topic', 'answers'])->find($id);

        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'Question not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $question
        ]);
    }

    /**
     * Update the specified question
     */
    public function update(Request $request, string $id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'Question not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_topic_id' => 'required|exists:topics,topic_id',
            'question_type_id' => 'required|integer',
            'question_difficulty_id' => 'required|integer',
            'question_score' => 'nullable|numeric',
            'answers' => 'nullable|array|min:2',
            'answers.*.answer_text' => 'required_with:answers|string',
            'answers.*.answer_is_correct' => 'required_with:answers|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $question->update($request->only([
            'question_text',
            'question_topic_id',
            'question_type_id',
            'question_difficulty_id',
            'question_score'
        ]));

        // Update answers if provided
        if ($request->has('answers')) {
            $question->answers()->delete();
            foreach ($request->answers as $answerData) {
                $question->answers()->create($answerData);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $question->load(['topic', 'answers']),
            'message' => 'Question updated successfully'
        ]);
    }

    /**
     * Remove the specified question
     */
    public function destroy(string $id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'Question not found'
            ], 404);
        }

        // Delete answers first
        $question->answers()->delete();
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully'
        ]);
    }
}
