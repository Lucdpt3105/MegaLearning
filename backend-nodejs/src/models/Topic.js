import { DataTypes } from 'sequelize';
import sequelize from '../config/database.js';

const Topic = sequelize.define('Topic', {
  topicId: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true,
    field: 'topic_id'
  },
  subjectId: {
    type: DataTypes.INTEGER,
    allowNull: false,
    field: 'subject_id'
  },
  name: {
    type: DataTypes.STRING,
    allowNull: false
  },
  description: {
    type: DataTypes.TEXT
  },
  displayOrder: {
    type: DataTypes.INTEGER,
    field: 'display_order'
  },
  active: {
    type: DataTypes.BOOLEAN,
    allowNull: false,
    defaultValue: true
  }
}, {
  tableName: 'topics',
  timestamps: true,
  createdAt: 'created_at',
  updatedAt: 'updated_at'
});

export default Topic;
