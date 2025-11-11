<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects with topics count
     * Public access - anyone can view
     */
    public function index()
    {
        $subjects = Subject::withCount('topics')
            ->with('topics')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $subjects,
            'message' => 'Subjects retrieved successfully'
        ]);
    }

    /**
     * Store a newly created subject
     * Permission check handled by route middleware
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_name' => 'required|string|max:100|unique:subjects,subject_name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subject = Subject::create([
            'subject_name' => $request->subject_name
        ]);

        return response()->json([
            'success' => true,
            'data' => $subject,
            'message' => 'Subject created successfully'
        ], 201);
    }

    /**
     * Display the specified subject with topics
     */
    public function show(string $id)
    {
        $subject = Subject::with(['topics.questions'])
            ->withCount('topics')
            ->find($id);

        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $subject
        ]);
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, string $id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'subject_name' => 'required|string|max:100|unique:subjects,subject_name,' . $id . ',subject_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subject->update([
            'subject_name' => $request->subject_name
        ]);

        return response()->json([
            'success' => true,
            'data' => $subject,
            'message' => 'Subject updated successfully'
        ]);
    }

    /**
     * Remove the specified subject
     */
    public function destroy(string $id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject not found'
            ], 404);
        }

        // Check if subject has topics
        if ($subject->topics()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete subject with existing topics'
            ], 400);
        }

        $subject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subject deleted successfully'
        ]);
    }
}
