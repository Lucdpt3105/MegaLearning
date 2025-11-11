import express from 'express';
import {
  getAllSubjects,
  getSubjectById,
  createSubject,
  updateSubject,
  deleteSubject
} from '../controllers/subjectController.js';
import { auth, requireRole } from '../middleware/auth.js';

const router = express.Router();

router.get('/', auth, getAllSubjects);
router.get('/:id', auth, getSubjectById);
router.post('/', auth, requireRole('ADMIN', 'TEACHER'), createSubject);
router.put('/:id', auth, requireRole('ADMIN', 'TEACHER'), updateSubject);
router.delete('/:id', auth, requireRole('ADMIN', 'TEACHER'), deleteSubject);

export default router;
