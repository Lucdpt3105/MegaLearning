import express from 'express';
import {
  getAllTopics,
  getTopicById,
  createTopic,
  updateTopic,
  deleteTopic
} from '../controllers/topicController.js';
import { auth, requireRole } from '../middleware/auth.js';

const router = express.Router();

router.get('/', auth, getAllTopics);
router.get('/:id', auth, getTopicById);
router.post('/', auth, requireRole('ADMIN', 'TEACHER'), createTopic);
router.put('/:id', auth, requireRole('ADMIN', 'TEACHER'), updateTopic);
router.delete('/:id', auth, requireRole('ADMIN', 'TEACHER'), deleteTopic);

export default router;
