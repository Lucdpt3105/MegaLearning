import { Topic, Subject } from '../models/index.js';

export const getAllTopics = async (req, res) => {
  try {
    const { subjectId } = req.query;
    
    const whereClause = { active: true };
    if (subjectId) {
      whereClause.subjectId = subjectId;
    }

    const topics = await Topic.findAll({
      where: whereClause,
      include: [{
        model: Subject,
        as: 'subject',
        attributes: ['subjectId', 'name']
      }],
      order: [['name', 'ASC']]
    });
    
    res.json({
      success: true,
      data: topics,
      count: topics.length
    });
  } catch (error) {
    console.error('Get topics error:', error);
    res.status(500).json({ 
      error: 'Failed to fetch topics',
      message: error.message 
    });
  }
};

export const getTopicById = async (req, res) => {
  try {
    const { id } = req.params;
    const topic = await Topic.findByPk(id, {
      include: [{
        model: Subject,
        as: 'subject',
        attributes: ['subjectId', 'name', 'description']
      }]
    });
    
    if (!topic || !topic.active) {
      return res.status(404).json({ error: 'Topic not found' });
    }
    
    res.json({
      success: true,
      data: topic
    });
  } catch (error) {
    console.error('Get topic error:', error);
    res.status(500).json({ 
      error: 'Failed to fetch topic',
      message: error.message 
    });
  }
};

export const createTopic = async (req, res) => {
  try {
    const { subjectId, name, description, content } = req.body;
    
    // Validation
    if (!name || name.trim().length === 0) {
      return res.status(400).json({ error: 'Topic name is required' });
    }

    if (!subjectId) {
      return res.status(400).json({ error: 'Subject ID is required' });
    }

    if (name.length > 200) {
      return res.status(400).json({ error: 'Topic name is too long (max 200 characters)' });
    }

    // Check if subject exists
    const subject = await Subject.findByPk(subjectId);
    if (!subject || !subject.active) {
      return res.status(404).json({ error: 'Subject not found' });
    }
    
    const topic = await Topic.create({
      subjectId,
      name: name.trim(),
      description: description?.trim() || null,
      content: content?.trim() || null,
      active: true
    });
    
    res.status(201).json({
      success: true,
      message: 'Topic created successfully',
      data: topic
    });
  } catch (error) {
    console.error('Create topic error:', error);
    res.status(500).json({ 
      error: 'Failed to create topic',
      message: error.message 
    });
  }
};

export const updateTopic = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, description, content } = req.body;
    
    const topic = await Topic.findByPk(id);
    if (!topic || !topic.active) {
      return res.status(404).json({ error: 'Topic not found' });
    }

    // Validation
    if (name && name.trim().length === 0) {
      return res.status(400).json({ error: 'Topic name cannot be empty' });
    }

    if (name && name.length > 200) {
      return res.status(400).json({ error: 'Topic name is too long (max 200 characters)' });
    }
    
    await topic.update({
      name: name ? name.trim() : topic.name,
      description: description !== undefined ? (description?.trim() || null) : topic.description,
      content: content !== undefined ? (content?.trim() || null) : topic.content
    });
    
    res.json({
      success: true,
      message: 'Topic updated successfully',
      data: topic
    });
  } catch (error) {
    console.error('Update topic error:', error);
    res.status(500).json({ 
      error: 'Failed to update topic',
      message: error.message 
    });
  }
};

export const deleteTopic = async (req, res) => {
  try {
    const { id } = req.params;
    
    const topic = await Topic.findByPk(id);
    if (!topic) {
      return res.status(404).json({ error: 'Topic not found' });
    }
    
    await topic.update({ active: false });
    
    res.json({ 
      success: true,
      message: 'Topic deleted successfully' 
    });
  } catch (error) {
    console.error('Delete topic error:', error);
    res.status(500).json({ 
      error: 'Failed to delete topic',
      message: error.message 
    });
  }
};
