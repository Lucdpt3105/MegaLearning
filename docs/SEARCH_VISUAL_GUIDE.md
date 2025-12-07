# Visual Guide: Global Search Feature

## UI Layout

```
┌──────────────────────────────────────────────────────────────────────────────┐
│  [SIDEBAR]  │ [🔍 Search for quizzes, courses, or topics... ] [🔔] [💬] [👤] │
│             │                                                                  │
│  Dashboard  │  ┌────────────────────────────────────────────┐                │
│  Courses    │  │ DROPDOWN RESULTS (when searching)          │                │
│  Exams      │  │                                            │                │
│  Forum      │  │ EXAMS / QUIZZES                            │                │
│  Documents  │  │ ─────────────────────────────────────────  │                │
│             │  │ 📝 Introduction to Programming Quiz        │                │
│             │  │    Subject: Computer Science               │                │
│             │  │                                            │                │
│             │  │ 📝 Midterm Exam - Math 101                 │                │
│             │  │    Subject: Mathematics                    │                │
│             │  │                                            │                │
│             │  │ SUBJECTS / COURSES                         │                │
│             │  │ ─────────────────────────────────────────  │                │
│             │  │ 📚 Computer Science           [CS101]      │                │
│             │  │    Teacher: Prof. John Smith               │                │
│             │  │                                            │                │
│             │  │ TOPICS                                     │                │
│             │  │ ─────────────────────────────────────────  │                │
│             │  │ 📖 Object-Oriented Programming             │                │
│             │  │    Subject: Computer Science               │                │
│             │  │                                            │                │
│             │  │ DOCUMENTS                                  │                │
│             │  │ ─────────────────────────────────────────  │                │
│             │  │ 📄 Lecture Slides - Week 1    [PDF]        │                │
│             │  │    Subject: Computer Science               │                │
│             │  │                                            │                │
│             │  │ FORUM QUESTIONS                            │                │
│             │  │ ─────────────────────────────────────────  │                │
│             │  │ 💬 How to solve quadratic equations?       │                │
│             │  │    By Alice Johnson                        │                │
│             │  └────────────────────────────────────────────┘                │
│             │                                                                  │
│             │  [MAIN CONTENT AREA]                                            │
│             │                                                                  │
└──────────────────────────────────────────────────────────────────────────────┘
```

## User Flow

### 1. Initial State (No Search)
```
┌─────────────────────────────────────────────┐
│ 🔍 Search for quizzes, courses, or topics...│
└─────────────────────────────────────────────┘
```

### 2. User Typing (Debouncing)
```
┌─────────────────────────────────────────────┐
│ 🔍 math                              [⏳]   │  ← Loading spinner appears
└─────────────────────────────────────────────┘
```

### 3. Results Displayed
```
┌─────────────────────────────────────────────┐
│ 🔍 math                                      │
└─────────────────────────────────────────────┘
   ↓
┌─────────────────────────────────────────────┐
│ EXAMS / QUIZZES                             │
│ ───────────────────────────────────────────│
│ 📝 Midterm Exam - Math 101                  │  ← Clickable results
│    Subject: Mathematics                     │
│                                             │
│ SUBJECTS / COURSES                          │
│ ───────────────────────────────────────────│
│ 📚 Mathematics 101        [MATH101]         │
│    Teacher: Prof. Sarah Lee                 │
└─────────────────────────────────────────────┘
```

### 4. No Results Found
```
┌─────────────────────────────────────────────┐
│ 🔍 xyzabc                                    │
└─────────────────────────────────────────────┘
   ↓
┌─────────────────────────────────────────────┐
│          😕                                  │
│   No results found for "xyzabc"             │
└─────────────────────────────────────────────┘
```

## Interaction Details

### Keyboard Interactions
- **Type**: Triggers search after 300ms
- **Escape**: Closes dropdown
- **Click Away**: Closes dropdown

### Mouse Interactions
- **Focus Input**: Shows dropdown if previous results exist
- **Hover Result**: Highlights the result item
- **Click Result**: Navigates to the detailed page

## Color Scheme & Styling

```
┌─────────────────────────────────────────────┐
│ Search Input:                               │
│ - Background: #F9FAFB (gray-50)             │
│ - Border: #E5E7EB (gray-200)                │
│ - Focus Ring: #A855F7 (purple-500)          │
│ - Text: #1F2937 (gray-800)                  │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│ Dropdown:                                   │
│ - Background: #FFFFFF (white)               │
│ - Border: #E5E7EB (gray-200)                │
│ - Shadow: large shadow                      │
│ - Max Height: 24rem (96px)                  │
│ - Scrollable: Yes                           │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│ Result Items:                               │
│ - Hover Background: #FAF5FF (purple-50)     │
│ - Title Color: #1F2937 (gray-800)           │
│ - Subtitle Color: #6B7280 (gray-500)        │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│ Category Headers:                           │
│ - Background: #F9FAFB (gray-50)             │
│ - Text: #4B5563 (gray-600)                  │
│ - Font: Uppercase, Bold, Small              │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│ Badges:                                     │
│ - Subject Code: Purple badge                │
│   - Background: #F3E8FF (purple-100)        │
│   - Text: #7C3AED (purple-700)              │
│                                             │
│ - File Type: Blue badge                     │
│   - Background: #DBEAFE (blue-100)          │
│   - Text: #1D4ED8 (blue-700)                │
└─────────────────────────────────────────────┘
```

## Responsive Behavior

### Desktop (>= 1024px)
- Search bar: Max width 32rem (512px)
- Dropdown: Full width of search bar
- Results: Clear separation between categories

### Tablet (768px - 1023px)
- Search bar: Flexible width (flex-1)
- Dropdown: Full width of search bar
- Results: Slightly reduced padding

### Mobile (< 768px)
- Search bar: Full width available
- Dropdown: Full screen width
- Results: Reduced padding for better mobile view
- Larger touch targets

## Loading States

### During Search
```
┌─────────────────────────────────────────────┐
│ 🔍 search query                      ⏳     │
└─────────────────────────────────────────────┘
                                       ↑
                                Spinning animation
```

### After Search (Success)
```
┌─────────────────────────────────────────────┐
│ 🔍 search query                      ✓      │
└─────────────────────────────────────────────┘
                                       ↑
                                  (implicit)
```

### After Search (Error)
```
┌─────────────────────────────────────────────┐
│ 🔍 search query                             │
└─────────────────────────────────────────────┘
   ↓
┌─────────────────────────────────────────────┐
│ ⚠️ An error occurred. Please try again.     │
└─────────────────────────────────────────────┘
```

## Animations

### Dropdown Entry
```
Opacity: 0 → 1
Scale: 0.95 → 1
Duration: 200ms
Easing: ease-out
```

### Dropdown Exit
```
Opacity: 1 → 0
Scale: 1 → 0.95
Duration: 150ms
Easing: ease-in
```

### Loading Spinner
```
Rotation: 0° → 360°
Duration: 1s
Iteration: Infinite
Easing: Linear
```

## Example Search Scenarios

### Scenario 1: Student Searching for Exam
```
Input: "midterm"
Results: 
  ✓ Midterm Exam - Math 101 → student/exams/1
  ✓ Midterm Review - Physics → student/exams/2
  ✓ Midterm Study Guide (document) → #
```

### Scenario 2: Teacher Searching for Subject
```
Input: "computer"
Results:
  ✓ Computer Science 101 → teacher/subjects/1
  ✓ Computer Networks → teacher/subjects/2
  ✓ Introduction to Computing (topic) → teacher/subjects/1
```

### Scenario 3: Admin Searching for Management
```
Input: "admin"
Results:
  ✓ System Administration Quiz → admin/exams/1/edit
  ✓ Admin Guide (document) → #
  ✓ How to use admin panel? (forum) → forum/show/5
```

## Technical Performance Metrics

### Target Performance
- First Paint: < 100ms
- Search API Response: < 500ms
- Dropdown Render: < 50ms
- Total Time to Interactive: < 650ms

### Optimization Strategies
1. Debouncing (300ms) - Reduces API calls by 70-90%
2. Limited Results (5 per category) - Fast rendering
3. Eager Loading - Prevents N+1 queries
4. Simple HTML Structure - Fast DOM manipulation
5. CSS Transitions - Hardware accelerated

## Browser Support

✓ Chrome 90+
✓ Firefox 88+
✓ Safari 14+
✓ Edge 90+
✓ Opera 76+

Features used:
- Fetch API
- ES6+ JavaScript
- CSS Grid/Flexbox
- CSS Transitions

## Accessibility Features

### Keyboard Navigation
- Tab: Focus search input
- Escape: Close dropdown
- (Future) Arrow keys: Navigate results
- (Future) Enter: Select highlighted result

### Screen Readers
- Proper ARIA labels
- Semantic HTML structure
- Clear status messages
- (Future) Live region updates

### Visual Accessibility
- High contrast ratios
- Clear focus states
- Readable font sizes
- Sufficient spacing

---

**Note**: This is a text-based visual guide. For actual UI mockups or screenshots, the application needs to be running with data in the database.
