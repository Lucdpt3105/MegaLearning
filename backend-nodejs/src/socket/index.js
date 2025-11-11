import { Server } from 'socket.io';
import { verifyToken } from '../utils/jwt.js';
import { ChatMessage, User } from '../models/index.js';
import { aiService } from '../services/aiService.js';

export const setupSocket = (server) => {
  const io = new Server(server, {
    cors: {
      origin: process.env.CORS_ORIGIN?.split(',') || ['http://localhost:5173'],
      methods: ['GET', 'POST'],
      credentials: true
    }
  });

  // Authentication middleware
  io.use(async (socket, next) => {
    try {
      const token = socket.handshake.auth.token;
      if (!token) {
        return next(new Error('Authentication error'));
      }

      const decoded = verifyToken(token);
      if (!decoded) {
        return next(new Error('Invalid token'));
      }

      socket.userId = decoded.userId;
      socket.userEmail = decoded.email;
      next();
    } catch (error) {
      next(new Error('Authentication error'));
    }
  });

  io.on('connection', (socket) => {
    console.log(`✅ User connected: ${socket.userEmail}`);

    // Join room
    socket.on('join-room', (roomId) => {
      socket.join(`room-${roomId}`);
      console.log(`User ${socket.userEmail} joined room ${roomId}`);
    });

    // Leave room
    socket.on('leave-room', (roomId) => {
      socket.leave(`room-${roomId}`);
      console.log(`User ${socket.userEmail} left room ${roomId}`);
    });

    // Send message
    socket.on('send-message', async (data) => {
      try {
        const { roomId, message } = data;

        // Save message to database
        const newMessage = await ChatMessage.create({
          roomId,
          userId: socket.userId,
          message,
          isAiResponse: false
        });

        // Load with user info
        const messageWithUser = await ChatMessage.findByPk(newMessage.messageId, {
          include: [{
            model: User,
            as: 'user',
            attributes: ['userId', 'name', 'email', 'role']
          }]
        });

        // Broadcast to room
        io.to(`room-${roomId}`).emit('new-message', messageWithUser);

        // Trigger AI if needed
        if (aiService.shouldRespond(message)) {
          const aiMessage = await aiService.generateAndSaveResponse(roomId, message);
          if (aiMessage) {
            // Broadcast AI response
            io.to(`room-${roomId}`).emit('new-message', aiMessage);
          }
        }
      } catch (error) {
        console.error('Socket message error:', error);
        socket.emit('error', { message: 'Failed to send message' });
      }
    });

    socket.on('disconnect', () => {
      console.log(`❌ User disconnected: ${socket.userEmail}`);
    });
  });

  return io;
};
