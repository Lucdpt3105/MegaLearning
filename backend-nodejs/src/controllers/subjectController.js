import { Subject, Topic } from '../models/index.js';

export const getAllSubjects = async (req, res) => {
  try {
    const subjects = await Subject.findAll({
      where: { active: true },
      include: [{
        model: Topic,
        as: 'topics',
        where: { active: true },
        required: false,
        attributes: ['topicId', 'name', 'description']
      }],
      order: [['name', 'ASC'], ['topics', 'name', 'ASC']]
    });
    
    res.json({
      success: true,
      data: subjects,
      count: subjects.length
    });
  } catch (error) {
    console.error('Get subjects error:', error);
    res.status(500).json({ 
      error: 'Failed to fetch subjects',
      message: error.message 
    });
  }
};

export const getSubjectById = async (req, res) => {
  try {
    const { id } = req.params;
    const subject = await Subject.findByPk(id, {
      include: [{
        model: Topic,
        as: 'topics',
        where: { active: true },
        required: false,
        order: [['name', 'ASC']]
      }]
    });
    
    if (!subject || !subject.active) {
      return res.status(404).json({ error: 'Subject not found' });
    }
    
    res.json({
      success: true,
      data: subject
    });
  } catch (error) {
    console.error('Get subject error:', error);
    res.status(500).json({ 
      error: 'Failed to fetch subject',
      message: error.message 
    });
  }
};

export const createSubject = async (req, res) => {
  try {
    const { name, description, thumbnailUrl } = req.body;
    
    // Validation
    if (!name || name.trim().length === 0) {
      return res.status(400).json({ error: 'Subject name is required' });
    }

    if (name.length > 200) {
      return res.status(400).json({ error: 'Subject name is too long (max 200 characters)' });
    }
    
    const subject = await Subject.create({
      name: name.trim(),
      description: description?.trim() || null,
      thumbnailUrl: thumbnailUrl || null,
      active: true
    });
    
    res.status(201).json({
      success: true,
      message: 'Subject created successfully',
      data: subject
    });
  } catch (error) {
    console.error('Create subject error:', error);
    res.status(500).json({ 
      error: 'Failed to create subject',
      message: error.message 
    });
  }
};

export const updateSubject = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, description, thumbnailUrl } = req.body;
    
    const subject = await Subject.findByPk(id);
    if (!subject || !subject.active) {
      return res.status(404).json({ error: 'Subject not found' });
    }

    // Validation
    if (name && name.trim().length === 0) {
      return res.status(400).json({ error: 'Subject name cannot be empty' });
    }

    if (name && name.length > 200) {
      return res.status(400).json({ error: 'Subject name is too long (max 200 characters)' });
    }
    
    await subject.update({
      name: name ? name.trim() : subject.name,
      description: description !== undefined ? (description?.trim() || null) : subject.description,
      thumbnailUrl: thumbnailUrl !== undefined ? thumbnailUrl : subject.thumbnailUrl
    });
    
    res.json({
      success: true,
      message: 'Subject updated successfully',
      data: subject
    });
  } catch (error) {
    console.error('Update subject error:', error);
    res.status(500).json({ 
      error: 'Failed to update subject',
      message: error.message 
    });
  }
};

export const deleteSubject = async (req, res) => {
  try {
    const { id } = req.params;
    
    const subject = await Subject.findByPk(id);
    if (!subject) {
      return res.status(404).json({ error: 'Subject not found' });
    }
    
    await subject.update({ active: false });
    
    res.json({ 
      success: true,
      message: 'Subject deleted successfully' 
    });
  } catch (error) {
    console.error('Delete subject error:', error);
    res.status(500).json({ 
      error: 'Failed to delete subject',
      message: error.message 
    });
  }
};
