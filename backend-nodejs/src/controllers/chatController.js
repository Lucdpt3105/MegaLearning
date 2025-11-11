import { ChatRoom, ChatMessage, User } from '../models/index.js';
import { aiService } from '../services/aiService.js';

export const getUserRooms = async (req, res) => {
  try {
    const user = await User.findByPk(req.user.userId, {
      include: [{
        model: ChatRoom,
        as: 'chatRooms',
        where: { active: true },
        required: false,
        include: [{
          model: ChatMessage,
          as: 'messages',
          limit: 1,
          order: [['createdAt', 'DESC']],
          required: false,
          include: [{
            model: User,
            as: 'user',
            attributes: ['userId', 'name']
          }]
        }]
      }]
    });

    const rooms = user.chatRooms || [];
    
    res.json({
      success: true,
      data: rooms,
      count: rooms.length
    });
  } catch (error) {
    console.error('Get rooms error:', error);
    res.status(500).json({ 
      error: 'Failed to fetch rooms',
      message: error.message 
    });
  }
};

export const getRoomMessages = async (req, res) => {
  try {
    const { roomId } = req.params;
    const limit = parseInt(req.query.limit) || 100;
    const offset = parseInt(req.query.offset) || 0;

    // Verify user has access to room
    const user = await User.findByPk(req.user.userId, {
      include: [{
        model: ChatRoom,
        as: 'chatRooms',
        where: { roomId, active: true },
        required: false
      }]
    });

    if (!user.chatRooms || user.chatRooms.length === 0) {
      return res.status(403).json({ error: 'Access denied to this room' });
    }

    const messages = await ChatMessage.findAll({
      where: { roomId },
      include: [{
        model: User,
        as: 'user',
        attributes: ['userId', 'name', 'email', 'role']
      }],
      order: [['createdAt', 'DESC']],
      limit,
      offset
    });

    res.json({
      success: true,
      data: messages.reverse(),
      count: messages.length
    });
  } catch (error) {
    console.error('Get messages error:', error);
    res.status(500).json({ 
      error: 'Failed to fetch messages',
      message: error.message 
    });
  }
};

export const sendMessage = async (req, res) => {
  try {
    const { roomId } = req.params;
    const { message } = req.body;
    const userId = req.user.userId;

    // Validation
    if (!message || message.trim().length === 0) {
      return res.status(400).json({ error: 'Message cannot be empty' });
    }

    if (message.length > 5000) {
      return res.status(400).json({ error: 'Message is too long (max 5000 characters)' });
    }

    // Verify room exists and user has access
    const user = await User.findByPk(userId, {
      include: [{
        model: ChatRoom,
        as: 'chatRooms',
        where: { roomId, active: true },
        required: false
      }]
    });

    if (!user.chatRooms || user.chatRooms.length === 0) {
      return res.status(403).json({ error: 'Access denied to this room' });
    }

    // Create message
    const newMessage = await ChatMessage.create({
      roomId,
      userId,
      message: message.trim(),
      isAiResponse: false
    });

    // Load user info
    const messageWithUser = await ChatMessage.findByPk(newMessage.messageId, {
      include: [{
        model: User,
        as: 'user',
        attributes: ['userId', 'name', 'email', 'role']
      }]
    });

    // Check if room has AI enabled and trigger AI response
    const room = await ChatRoom.findByPk(roomId);
    if (room && room.isAiEnabled && aiService.shouldRespond(message)) {
      // Trigger AI response asynchronously (don't wait)
      aiService.generateAndSaveResponse(roomId, message).catch(err => {
        console.error('AI response error:', err);
      });
    }

    res.status(201).json({
      success: true,
      message: 'Message sent successfully',
      data: messageWithUser
    });
  } catch (error) {
    console.error('Send message error:', error);
    res.status(500).json({ 
      error: 'Failed to send message',
      message: error.message 
    });
  }
};

export const createRoom = async (req, res) => {
  try {
    const { name, description, type = 'GROUP', isAiEnabled = false } = req.body;
    const userId = req.user.userId;

    // Validation
    if (!name || name.trim().length === 0) {
      return res.status(400).json({ error: 'Room name is required' });
    }

    if (name.length > 100) {
      return res.status(400).json({ error: 'Room name is too long (max 100 characters)' });
    }

    // Create room
    const room = await ChatRoom.create({
      name: name.trim(),
      description: description?.trim() || null,
      type,
      isAiEnabled,
      active: true
    });

    // Add creator as member
    const user = await User.findByPk(userId);
    await room.addMember(user);

    // Add AI user if enabled
    if (isAiEnabled) {
      const aiUser = await User.findOne({ where: { email: 'ai@megalearning.com' } });
      if (aiUser) {
        await room.addMember(aiUser);
      }
    }

    res.status(201).json({
      success: true,
      message: 'Room created successfully',
      data: room
    });
  } catch (error) {
    console.error('Create room error:', error);
    res.status(500).json({ 
      error: 'Failed to create room',
      message: error.message 
    });
  }
};

export const deleteRoom = async (req, res) => {
  try {
    const { roomId } = req.params;
    const userId = req.user.userId;

    const room = await ChatRoom.findByPk(roomId);
    
    if (!room) {
      return res.status(404).json({ error: 'Room not found' });
    }

    // Soft delete
    await room.update({ active: false });

    res.json({
      success: true,
      message: 'Room deleted successfully'
    });
  } catch (error) {
    console.error('Delete room error:', error);
    res.status(500).json({ 
      error: 'Failed to delete room',
      message: error.message 
    });
  }
};
