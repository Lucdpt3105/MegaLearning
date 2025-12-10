# MegaLearning Admin API - Thunder Client Test Guide

Base URL: `http://localhost:8000`

---

## 🚀 Quick Start (Dev Token - FASTEST!)

```
POST http://localhost:8000/api/dev-token
```
**No body needed!** Returns token immediately. Copy `access_token` từ response.

---

## 📋 ALL AVAILABLE APIs

### 🔐 Authentication

#### 1. Get Dev Token (Quick)
```
POST /api/dev-token
```

#### 2. Login (Normal)
```
POST /api/login
Content-Type: application/json

{
  "email": "admin@megalearning.com",
  "password": "password"
}
```

#### 3. Logout
```
POST /api/logout
Authorization: Bearer YOUR_TOKEN
```

#### 4. Get Current User
```
GET /api/me
Authorization: Bearer YOUR_TOKEN
```

---

### 📁 Categories (NEW!)

#### Get All Categories (Public)
```
GET /api/v1/categories
```
Returns: 6 categories đã seed

#### Get Single Category (Public)
```
GET /api/v1/categories/{id}
```

#### Create Category (Protected - Need Admin)
```
POST /api/v1/categories
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "name": "Marketing Digital",
  "description": "Khóa học về marketing online",
  "is_active": true
}
```

#### Update Category (Protected)
```
PUT /api/v1/categories/{id}
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "name": "Marketing Digital (Updated)",
  "description": "Mô tả mới"
}
```

#### Delete Category (Protected)
```
DELETE /api/v1/categories/{id}
Authorization: Bearer YOUR_TOKEN
```

---

### 📚 Subjects

#### Get All Subjects
```
GET /api/v1/subjects
```

#### Create Subject (Protected)
```
POST /api/v1/subjects
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "name": "Toán cao cấp",
  "description": "Môn toán nâng cao",
  "code": "MATH301",
  "credits": 3
}
```

#### Update Subject
```
PUT /api/v1/subjects/{id}
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "name": "Toán cao cấp A1"
}
```

#### Delete Subject
```
DELETE /api/v1/subjects/{id}
Authorization: Bearer YOUR_TOKEN
```

---

### 📖 Topics

#### Get All Topics
```
GET /api/v1/topics
```

#### Create Topic (Protected)
```
POST /api/v1/topics
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "subject_id": 1,
  "title": "Đại số tuyến tính",
  "description": "Chương 1"
}
```

#### Update Topic
```
PUT /api/v1/topics/{id}
Authorization: Bearer YOUR_TOKEN
```

#### Delete Topic
```
DELETE /api/v1/topics/{id}
Authorization: Bearer YOUR_TOKEN
```

---

### ❓ Questions

#### Get All Questions
```
GET /api/v1/questions
```

#### Create Question (Protected)
```
POST /api/v1/questions
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "subject_id": 1,
  "topic_id": 1,
  "question_text": "2 + 2 = ?",
  "type": "multiple_choice",
  "difficulty": "easy",
  "points": 10
}
```

#### Update Question
```
PUT /api/v1/questions/{id}
Authorization: Bearer YOUR_TOKEN
```

#### Delete Question
```
DELETE /api/v1/questions/{id}
Authorization: Bearer YOUR_TOKEN
```

---

### 📝 Exams

#### Get All Exams
```
GET /api/v1/exams
```

#### Create Exam (Protected)
```
POST /api/v1/exams
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "title": "Kiểm tra giữa kỳ Toán",
  "subject_id": 1,
  "duration": 60,
  "passing_score": 50,
  "type": "midterm"
}
```

#### Update Exam
```
PUT /api/v1/exams/{id}
Authorization: Bearer YOUR_TOKEN
```

#### Delete Exam
```
DELETE /api/v1/exams/{id}
Authorization: Bearer YOUR_TOKEN
```

---

### 💬 Chat (Real-time)

#### Get Current User
```
GET /api/v1/chat/current-user
Authorization: Bearer YOUR_TOKEN
```

#### Get All Rooms
```
GET /api/v1/chat/rooms
Authorization: Bearer YOUR_TOKEN
```

#### Get Messages in Room
```
GET /api/v1/chat/rooms/{roomId}/messages
Authorization: Bearer YOUR_TOKEN
```

#### Send Message
```
POST /api/v1/chat/rooms/{roomId}/messages
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "message": "Hello everyone!"
}
```

#### Create Private Room
```
POST /api/v1/chat/rooms/private
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "user_id": 2
}
```

#### Get Unread Count
```
GET /api/v1/chat/unread-count
Authorization: Bearer YOUR_TOKEN
```

---

## 🧪 Testing Workflow

### Step 1: Get Token
```
POST http://localhost:8000/api/dev-token
```
Copy `access_token`

### Step 2: Test Public APIs (No token needed)
```
GET http://localhost:8000/api/v1/categories
GET http://localhost:8000/api/v1/subjects
GET http://localhost:8000/api/v1/topics
GET http://localhost:8000/api/v1/questions
GET http://localhost:8000/api/v1/exams
```

### Step 3: Test Protected APIs (Need token)
```
POST http://localhost:8000/api/v1/categories
Headers:
  Authorization: Bearer YOUR_TOKEN
  Content-Type: application/json
Body:
{
  "name": "Test Category",
  "description": "Testing CRUD"
}
```

---

## 📊 Available Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@megalearning.com | password |
| Teacher | teacher@megalearning.com | password |
| Student | student@megalearning.com | password |

---

## ⚡ Quick Test Collection

### Test 1: Categories CRUD
1. GET all → See 6 categories
2. POST create → See new category
3. GET all → See 7 categories
4. PUT update → Change name
5. DELETE → Remove test category

### Test 2: Subjects CRUD
Same pattern as Categories

### Test 3: Chat
1. GET rooms → See available rooms
2. GET messages → See chat history
3. POST message → Send new message

---

## ❌ Common Errors

**401 Unauthenticated:**
- Missing token or invalid token
- Fix: Get new token from `/api/dev-token`

**403 Forbidden:**
- User doesn't have permission
- Fix: Use admin token

**422 Validation Error:**
- Missing required fields
- Fix: Check request body

**500 Internal Server Error:**
- Check Laravel logs: `storage/logs/laravel.log`

---

## 📌 Summary

**Total APIs:** 50+ endpoints

**Working & Tested:**
- ✅ Categories (6 endpoints)
- ✅ Subjects (5 endpoints)
- ✅ Topics (5 endpoints)
- ✅ Questions (5 endpoints)
- ✅ Exams (5 endpoints)
- ✅ Chat (10+ endpoints)
- ✅ Auth (4 endpoints)

**Most Common for Testing:**
1. `/api/dev-token` - Get token
2. `/api/v1/categories` - Test CRUD
3. `/api/v1/subjects` - Test CRUD
4. `/api/v1/chat/rooms` - Test chat
