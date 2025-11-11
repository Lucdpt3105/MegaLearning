# MegaLearning Backend - Node.js + Express

## 🚀 Tech Stack

- **Runtime**: Node.js 18+
- **Framework**: Express.js
- **Database**: MySQL + Sequelize ORM
- **Authentication**: JWT (jsonwebtoken)
- **Real-time**: Socket.IO
- **AI**: OpenAI API
- **Password**: bcryptjs

## 📦 Installation

```bash
# Install dependencies
npm install

# Copy environment file
copy .env.example .env

# Edit .env with your settings
```

## ⚙️ Configuration

Edit `.env`:

```env
# Database
DB_PASSWORD=your_mysql_password

# JWT Secret (min 32 characters)
JWT_SECRET=your-secret-key-change-this

# OpenAI (optional)
OPENAI_API_KEY=sk-your-key-here
```

## 🗄️ Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE learning3;

# Seed demo data
npm run seed
```

## 🏃 Run Server

```bash
# Development (with auto-reload)
npm run dev

# Production
npm start
```

Server runs on: **http://localhost:8080**

## 📡 API Endpoints

### Authentication
- `POST /api/auth/register` - Register
- `POST /api/auth/login` - Login

### Chat
- `GET /api/chat/rooms` - Get user rooms
- `GET /api/chat/rooms/:id/messages` - Get messages
- `POST /api/chat/rooms/:id/messages` - Send message
- `POST /api/chat/rooms` - Create room

### Subjects
- `GET /api/subjects` - List subjects
- `POST /api/subjects` - Create (Admin/Teacher)
- `PUT /api/subjects/:id` - Update (Admin/Teacher)
- `DELETE /api/subjects/:id` - Delete (Admin/Teacher)

## 🔌 Socket.IO Events

### Client → Server
- `join-room` - Join chat room
- `leave-room` - Leave room
- `send-message` - Send message

### Server → Client
- `new-message` - New message received
- `error` - Error occurred

## 👤 Demo Accounts

| Email | Password | Role |
|-------|----------|------|
| student1@test.com | 123456 | Student |
| teacher@test.com | 123456 | Teacher |
| admin@test.com | 123456 | Admin |

## 📁 Project Structure

```
backend-nodejs/
├── src/
│   ├── config/
│   │   └── database.js       # Sequelize config
│   ├── controllers/
│   │   ├── authController.js
│   │   ├── chatController.js
│   │   └── subjectController.js
│   ├── middleware/
│   │   └── auth.js           # JWT auth
│   ├── models/
│   │   ├── User.js
│   │   ├── ChatRoom.js
│   │   ├── ChatMessage.js
│   │   ├── Subject.js
│   │   └── index.js          # Model associations
│   ├── routes/
│   │   ├── authRoutes.js
│   │   ├── chatRoutes.js
│   │   ├── subjectRoutes.js
│   │   └── index.js
│   ├── services/
│   │   └── aiService.js      # OpenAI integration
│   ├── socket/
│   │   └── index.js          # Socket.IO setup
│   ├── utils/
│   │   └── jwt.js            # JWT helpers
│   ├── database/
│   │   └── seeders/
│   │       └── index.js      # Database seeder
│   └── server.js             # Main entry point
├── .env.example
├── package.json
└── README.md
```

## 🧪 Testing

```bash
# Test health endpoint
curl http://localhost:8080/api/health

# Test login
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"student1@test.com\",\"password\":\"123456\"}"
```

## 🐛 Troubleshooting

### Port already in use
```bash
# Find process on port 8080
netstat -ano | findstr :8080

# Kill it
taskkill /PID <PID> /F
```

### Database connection error
- Check MySQL is running
- Verify credentials in `.env`
- Ensure database `learning3` exists

## 📝 License

MIT - MegaLearning Team 2025
