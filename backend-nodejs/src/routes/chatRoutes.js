import express from 'express';
import {
  getUserRooms,
  getRoomMessages,
  sendMessage,
  createRoom,
  deleteRoom
} from '../controllers/chatController.js';
import { auth } from '../middleware/auth.js';

const router = express.Router();

router.use(auth); // All routes require authentication

router.get('/rooms', getUserRooms);
router.post('/rooms', createRoom);
router.delete('/rooms/:roomId', deleteRoom);
router.get('/rooms/:roomId/messages', getRoomMessages);
router.post('/rooms/:roomId/messages', sendMessage);

export default router;
