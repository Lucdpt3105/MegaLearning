import { DataTypes } from 'sequelize';
import sequelize from '../config/database.js';

const ChatRoom = sequelize.define('ChatRoom', {
  roomId: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true,
    field: 'room_id'
  },
  name: {
    type: DataTypes.STRING,
    allowNull: false
  },
  description: {
    type: DataTypes.TEXT
  },
  type: {
    type: DataTypes.ENUM('PRIVATE', 'GROUP', 'STUDY_GROUP'),
    allowNull: false,
    defaultValue: 'GROUP'
  },
  subjectId: {
    type: DataTypes.INTEGER,
    field: 'subject_id'
  },
  isAiEnabled: {
    type: DataTypes.BOOLEAN,
    allowNull: false,
    defaultValue: false,
    field: 'is_ai_enabled'
  },
  active: {
    type: DataTypes.BOOLEAN,
    allowNull: false,
    defaultValue: true
  }
}, {
  tableName: 'chat_rooms',
  timestamps: true,
  createdAt: 'created_at',
  updatedAt: 'updated_at'
});

export default ChatRoom;
