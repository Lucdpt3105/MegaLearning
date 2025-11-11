import authRoutes from './authRoutes.js';
import chatRoutes from './chatRoutes.js';
import subjectRoutes from './subjectRoutes.js';
import topicRoutes from './topicRoutes.js';

export default (app) => {
  // API routes
  app.use('/api/auth', authRoutes);
  app.use('/api/chat', chatRoutes);
  app.use('/api/subjects', subjectRoutes);
  app.use('/api/topics', topicRoutes);

  // API documentation endpoint
  app.get('/api', (req, res) => {
    res.json({
      message: 'MegaLearning API',
      version: '1.0.0',
      endpoints: {
        auth: {
          'POST /api/auth/register': 'Register new user',
          'POST /api/auth/login': 'Login user',
          'GET /api/auth/profile': 'Get user profile (requires auth)',
          'PUT /api/auth/profile': 'Update user profile (requires auth)'
        },
        chat: {
          'GET /api/chat/rooms': 'Get user chat rooms (requires auth)',
          'POST /api/chat/rooms': 'Create new chat room (requires auth)',
          'DELETE /api/chat/rooms/:roomId': 'Delete chat room (requires auth)',
          'GET /api/chat/rooms/:roomId/messages': 'Get room messages (requires auth)',
          'POST /api/chat/rooms/:roomId/messages': 'Send message to room (requires auth)'
        },
        subjects: {
          'GET /api/subjects': 'Get all subjects (requires auth)',
          'GET /api/subjects/:id': 'Get subject by ID (requires auth)',
          'POST /api/subjects': 'Create subject (requires ADMIN/TEACHER)',
          'PUT /api/subjects/:id': 'Update subject (requires ADMIN/TEACHER)',
          'DELETE /api/subjects/:id': 'Delete subject (requires ADMIN/TEACHER)'
        },
        topics: {
          'GET /api/topics': 'Get all topics (requires auth)',
          'GET /api/topics/:id': 'Get topic by ID (requires auth)',
          'POST /api/topics': 'Create topic (requires ADMIN/TEACHER)',
          'PUT /api/topics/:id': 'Update topic (requires ADMIN/TEACHER)',
          'DELETE /api/topics/:id': 'Delete topic (requires ADMIN/TEACHER)'
        }
      }
    });
  });
};
