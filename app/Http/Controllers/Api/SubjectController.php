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
        $subjects = Subject::withCount(['topics', 'exams'])
            ->get()
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                    'description' => $subject->description,
                    'topics_count' => $subject->topics_count,
                    'exams_count' => $subject->exams_count,
                    'status' => $subject->status,
                    'created_at' => $subject->created_at,
                    'updated_at' => $subject->updated_at,
                ];
            });

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
            'name' => 'required|string|max:100|unique:subjects,name',
            'code' => 'nullable|string|max:20|unique:subjects,code',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Auto-generate code if not provided
        $code = $request->code ?? strtoupper(substr(preg_replace('/[^A-Z]/i', '', $request->name), 0, 6));
        
        $subject = Subject::create([
            'name' => $request->name,
            'code' => $code,
            'description' => $request->description,
            'status' => 'active',
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
            'name' => 'required|string|max:100|unique:subjects,name,' . $id,
            'code' => 'nullable|string|max:20|unique:subjects,code,' . $id,
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subject->update($request->only(['name', 'code', 'description', 'status']));

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
