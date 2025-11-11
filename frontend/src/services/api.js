import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8080/api';

const axiosInstance = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
  timeout: 30000, // 30 seconds timeout
});

// Request interceptor to add JWT token
axiosInstance.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    console.error('Request error:', error);
    return Promise.reject(error);
  }
);

// Response interceptor to handle errors
axiosInstance.interceptors.response.use(
  (response) => {
    // Extract data if response has success wrapper
    if (response.data && response.data.success) {
      return { ...response, data: response.data.data };
    }
    return response;
  },
  (error) => {
    if (error.response) {
      // Handle 401 Unauthorized
      if (error.response.status === 401) {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
      }
      
      // Handle other errors
      const errorMessage = error.response.data?.error || error.response.data?.message || 'An error occurred';
      console.error('API Error:', errorMessage);
      
      return Promise.reject({
        message: errorMessage,
        status: error.response.status,
        data: error.response.data
      });
    } else if (error.request) {
      console.error('Network error:', error.message);
      return Promise.reject({
        message: 'Network error. Please check your connection.',
        status: 0
      });
    } else {
      console.error('Error:', error.message);
      return Promise.reject({
        message: error.message,
        status: 0
      });
    }
  }
);

// Auth API
export const authAPI = {
  login: (credentials) => axiosInstance.post('/auth/login', credentials),
  register: (userData) => axiosInstance.post('/auth/register', userData),
  getProfile: () => axiosInstance.get('/auth/profile'),
  updateProfile: (data) => axiosInstance.put('/auth/profile', data),
};

// Chat API
export const chatAPI = {
  getRooms: () => axiosInstance.get('/chat/rooms'),
  createRoom: (roomData) => axiosInstance.post('/chat/rooms', roomData),
  deleteRoom: (roomId) => axiosInstance.delete(`/chat/rooms/${roomId}`),
  getRoomMessages: (roomId, params) => axiosInstance.get(`/chat/rooms/${roomId}/messages`, { params }),
  sendMessage: (roomId, message) => axiosInstance.post(`/chat/rooms/${roomId}/messages`, { message }),
};

// Subject API
export const subjectAPI = {
  getAll: () => axiosInstance.get('/subjects'),
  getById: (id) => axiosInstance.get(`/subjects/${id}`),
  create: (data) => axiosInstance.post('/subjects', data),
  update: (id, data) => axiosInstance.put(`/subjects/${id}`, data),
  delete: (id) => axiosInstance.delete(`/subjects/${id}`),
};

// Topic API
export const topicAPI = {
  getAll: (params) => axiosInstance.get('/topics', { params }),
  getById: (id) => axiosInstance.get(`/topics/${id}`),
  create: (data) => axiosInstance.post('/topics', data),
  update: (id, data) => axiosInstance.put(`/topics/${id}`, data),
  delete: (id) => axiosInstance.delete(`/topics/${id}`),
};

export default axiosInstance;

