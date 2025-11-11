import { DataTypes } from 'sequelize';
import sequelize from '../config/database.js';

const User = sequelize.define('User', {
  userId: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true,
    field: 'user_id'
  },
  email: {
    type: DataTypes.STRING,
    allowNull: false,
    unique: true,
    validate: {
      isEmail: true
    }
  },
  password: {
    type: DataTypes.STRING,
    allowNull: false
  },
  name: {
    type: DataTypes.STRING,
    allowNull: false
  },
  phoneNumber: {
    type: DataTypes.STRING,
    field: 'phone_number'
  },
  dateOfBirth: {
    type: DataTypes.DATE,
    field: 'date_of_birth'
  },
  address: {
    type: DataTypes.STRING
  },
  avatarUrl: {
    type: DataTypes.STRING,
    field: 'avatar_url'
  },
  role: {
    type: DataTypes.ENUM('ADMIN', 'TEACHER', 'STUDENT', 'AI'),
    allowNull: false,
    defaultValue: 'STUDENT'
  },
  active: {
    type: DataTypes.BOOLEAN,
    allowNull: false,
    defaultValue: true
  }
}, {
  tableName: 'users',
  timestamps: true,
  createdAt: 'created_at',
  updatedAt: 'updated_at'
});

export default User;
