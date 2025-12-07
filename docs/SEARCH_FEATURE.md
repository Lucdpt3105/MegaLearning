# Global Search Feature Documentation

## Overview
The global search feature allows users to search across multiple content types in the MegaLearning platform from the header search bar.

## Implementation Details

### Search Capabilities
The search feature searches across the following content types:
1. **Exams/Quizzes** - Search by title and description
2. **Subjects/Courses** - Search by name, code, and description
3. **Topics** - Search by name and description
4. **Documents** - Search by title, description, and file name
5. **Forum Questions** - Search by title and content

### Files Modified/Created

#### 1. SearchController (`app/Http/Controllers/SearchController.php`)
- **Purpose**: Handles global search requests from the frontend
- **Main Method**: `search(Request $request)`
- **Features**:
  - Returns up to 5 results per content type
  - Filters results based on status (published exams, active subjects, approved documents)
  - Returns role-appropriate URLs for each result
  - Includes error handling with try-catch
  - Returns JSON response for AJAX requests

#### 2. Route Configuration (`routes/web.php`)
- **New Route**: `GET /search` (requires authentication)
- **Route Name**: `search`
- **Controller Method**: `SearchController@search`

#### 3. Header Template (`resources/views/layouts/partials/header.blade.php`)
- **Changes**:
  - Added search input with ID `global-search-input`
  - Added loading spinner during search
  - Added dropdown for displaying search results
  - Implemented vanilla JavaScript for:
    - Debounced search (300ms delay)
    - AJAX calls to search endpoint
    - Dynamic result rendering
    - Keyboard navigation (Escape to close)
    - Click-away detection

### User Experience

#### Search Behavior
1. User types in the search bar
2. After 300ms of no typing (debounce), search is triggered
3. Loading spinner appears while searching
4. Results dropdown appears with categorized results
5. Up to 5 results per category are shown
6. Each result shows relevant information and links to the appropriate page

#### Result Display
Results are categorized and displayed in the following order:
1. **Exams/Quizzes** - Shows title and subject
2. **Subjects/Courses** - Shows title, code, and teacher name
3. **Topics** - Shows title and subject
4. **Documents** - Shows title, subject, and file type badge
5. **Forum Questions** - Shows title and author

### Role-Based URL Generation

The search controller generates appropriate URLs based on user roles:

- **Students**:
  - Exams: `student.exams.show`
  - Subjects: `student.courses.index`
  - Topics: `student.courses.index`
  - Documents: `#` (placeholder)
  - Forum: `forum.show`

- **Teachers**:
  - Exams: `teacher.exams.show`
  - Subjects: `teacher.subjects.show`
  - Topics: `teacher.subjects.show`
  - Documents: `teacher.documents.show`
  - Forum: `forum.show`

- **Admins**:
  - Exams: `admin.exams.edit`
  - Subjects: `admin.subjects.show`
  - Topics: `admin.subjects.show`
  - Documents: `#` (placeholder)
  - Forum: `forum.show`

### Security Features

1. **Authentication Required**: Search route requires authenticated users
2. **Status Filtering**: 
   - Only published exams are searchable
   - Only active subjects are searchable
   - Only approved documents are searchable
3. **SQL Injection Prevention**: Uses Laravel's query builder with parameter binding
4. **XSS Prevention**: Uses `escapeHtml()` function in JavaScript to sanitize output
5. **Error Handling**: Catches and logs exceptions without exposing sensitive information

### Technical Specifications

#### API Request
```
GET /search?query={searchTerm}
Headers:
  X-Requested-With: XMLHttpRequest
  Accept: application/json
```

#### API Response Structure
```json
{
  "success": true,
  "results": {
    "exams": [
      {
        "id": 1,
        "title": "Exam Title",
        "description": "Exam Description",
        "subject": "Subject Name",
        "type": "exam",
        "url": "/student/exams/1"
      }
    ],
    "subjects": [...],
    "topics": [...],
    "documents": [...],
    "forum_questions": [...]
  },
  "total": 10,
  "query": "search term"
}
```

#### Error Response
```json
{
  "success": false,
  "message": "An error occurred while searching. Please try again.",
  "results": {
    "exams": [],
    "subjects": [],
    "topics": [],
    "documents": [],
    "forum_questions": []
  },
  "total": 0
}
```

### Performance Considerations

1. **Limit Results**: Maximum 5 results per category (25 total)
2. **Debouncing**: 300ms delay prevents excessive API calls
3. **Eager Loading**: Uses `with()` to prevent N+1 queries
4. **Index Optimization**: Ensure database indexes on searched columns:
   - `exams.title`, `exams.description`
   - `subjects.name`, `subjects.code`, `subjects.description`
   - `topics.name`, `topics.description`
   - `documents.title`, `documents.description`, `documents.file_name`
   - `forumquestions.title`, `forumquestions.content`

### Future Enhancements

Potential improvements for the search feature:
1. Add full-text search using MySQL FULLTEXT or Laravel Scout
2. Add search filters (by date, type, subject, etc.)
3. Add search history for users
4. Add autocomplete suggestions
5. Add search analytics to track popular searches
6. Add pagination for more than 5 results per category
7. Add keyboard navigation through results
8. Add highlighted search terms in results
9. Cache popular search results
10. Add search shortcuts (e.g., "exam:math" to search only math exams)

### Testing Guidelines

To test the search feature:

1. **Authentication Test**: Ensure search is only accessible when logged in
2. **Role-Based URL Test**: Verify URLs are correct for student, teacher, and admin roles
3. **Search Accuracy Test**: Verify search returns relevant results
4. **Empty Query Test**: Ensure empty searches return empty results
5. **Special Characters Test**: Test with special characters and Unicode
6. **Performance Test**: Test with large datasets to ensure acceptable response time
7. **Error Handling Test**: Simulate database errors to verify error handling
8. **UI Test**: Verify dropdown appears/disappears correctly
9. **Keyboard Test**: Test Escape key closes dropdown
10. **Mobile Test**: Ensure search works on mobile devices

### Maintenance Notes

When adding new searchable content types:
1. Add search logic to `SearchController@search`
2. Add result rendering in header JavaScript
3. Update this documentation
4. Add appropriate database indexes
5. Update test cases

### Troubleshooting

Common issues and solutions:

1. **Search returns no results**:
   - Check database has data with appropriate status
   - Verify user is authenticated
   - Check browser console for errors

2. **Dropdown doesn't appear**:
   - Check JavaScript console for errors
   - Verify route is configured correctly
   - Ensure CSRF token is valid

3. **URLs lead to 404**:
   - Verify routes exist for the user's role
   - Check route names in SearchController

4. **Slow search performance**:
   - Add database indexes on searched columns
   - Consider implementing caching
   - Reduce result limit or implement pagination
