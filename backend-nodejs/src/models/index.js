import sequelize from '../config/database.js';
import User from './User.js';
import Subject from './Subject.js';
import Topic from './Topic.js';
import ChatRoom from './ChatRoom.js';
import ChatMessage from './ChatMessage.js';

// Define associations
// Subject - Topic (One-to-Many)
Subject.hasMany(Topic, {
  foreignKey: 'subjectId',
  as: 'topics'
});
Topic.belongsTo(Subject, {
  foreignKey: 'subjectId',
  as: 'subject'
});

// ChatRoom - ChatMessage (One-to-Many)
ChatRoom.hasMany(ChatMessage, {
  foreignKey: 'roomId',
  as: 'messages'
});
ChatMessage.belongsTo(ChatRoom, {
  foreignKey: 'roomId',
  as: 'room'
});

// User - ChatMessage (One-to-Many)
User.hasMany(ChatMessage, {
  foreignKey: 'userId',
  as: 'messages'
});
ChatMessage.belongsTo(User, {
  foreignKey: 'userId',
  as: 'user'
});

// User - ChatRoom (Many-to-Many)
User.belongsToMany(ChatRoom, {
  through: 'chat_room_members',
  foreignKey: 'user_id',
  otherKey: 'room_id',
  as: 'chatRooms'
});
ChatRoom.belongsToMany(User, {
  through: 'chat_room_members',
  foreignKey: 'room_id',
  otherKey: 'user_id',
  as: 'members'
});

// Sync database
const syncDatabase = async () => {
  try {
    // Don't sync - use existing database structure
    // await sequelize.sync({ alter: true });
    await sequelize.authenticate();
    console.log('✅ Database connected and ready');
  } catch (error) {
    console.error('❌ Database connection error:', error);
  }
};

export {
  sequelize,
  User,
  Subject,
  Topic,
  ChatRoom,
  ChatMessage,
  syncDatabase
};
