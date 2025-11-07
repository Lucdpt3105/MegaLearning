<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    /**
     * Display all exams or filter by subject
     */
    public function index(Request $request)
    {
        $query = Exam::with('subject')->withCount('questions');

        if ($request->has('subject_id')) {
            $query->where('exam_subject_id', $request->subject_id);
        }

        $exams = $query->get();

        return response()->json([
            'success' => true,
            'data' => $exams
        ]);
    }

    /**
     * Store a newly created exam
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_name' => 'required|string|max:100',
            'exam_subject_id' => 'required|exists:subjects,subject_id',
            'exam_date' => 'nullable|date',
            'exam_duration' => 'nullable|integer|min:1',
            'exam_instructions' => 'nullable|string',
            'question_ids' => 'nullable|array',
            'question_ids.*' => 'exists:questions,question_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $exam = Exam::create($request->only([
            'exam_name',
            'exam_subject_id',
            'exam_date',
            'exam_duration',
            'exam_instructions'
        ]));

        // Attach questions to exam if provided
        if ($request->has('question_ids')) {
            $exam->questions()->attach($request->question_ids);
        }

        return response()->json([
            'success' => true,
            'data' => $exam->load(['subject', 'questions']),
            'message' => 'Exam created successfully'
        ], 201);
    }

    /**
     * Display the specified exam with questions
     */
    public function show(string $id)
    {
        $exam = Exam::with(['subject', 'questions.answers'])
            ->withCount('questions')
            ->find($id);

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $exam
        ]);
    }

    /**
     * Update the specified exam
     */
    public function update(Request $request, string $id)
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'exam_name' => 'required|string|max:100',
            'exam_subject_id' => 'required|exists:subjects,subject_id',
            'exam_date' => 'nullable|date',
            'exam_duration' => 'nullable|integer|min:1',
            'exam_instructions' => 'nullable|string',
            'question_ids' => 'nullable|array',
            'question_ids.*' => 'exists:questions,question_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $exam->update($request->only([
            'exam_name',
            'exam_subject_id',
            'exam_date',
            'exam_duration',
            'exam_instructions'
        ]));

        // Sync questions if provided
        if ($request->has('question_ids')) {
            $exam->questions()->sync($request->question_ids);
        }

        return response()->json([
            'success' => true,
            'data' => $exam->load(['subject', 'questions']),
            'message' => 'Exam updated successfully'
        ]);
    }

    /**
     * Remove the specified exam
     */
    public function destroy(string $id)
    {
        $exam = Exam::find($id);

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found'
            ], 404);
        }

        // Detach all questions first
        $exam->questions()->detach();
        $exam->delete();

        return response()->json([
            'success' => true,
            'message' => 'Exam deleted successfully'
        ]);
    }
}
