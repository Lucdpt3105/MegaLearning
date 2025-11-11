import React, { createContext, useState, useContext, useEffect } from 'react';
import { authAPI } from '../services/api';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Check if user is logged in
    const storedToken = localStorage.getItem('token');
    const storedUser = localStorage.getItem('user');
    
    if (storedToken && storedUser) {
      setToken(storedToken);
      setUser(JSON.parse(storedUser));
    }
    setLoading(false);
  }, []);

  const login = async (credentials) => {
    try {
      const response = await authAPI.login(credentials);
      
      // Handle new response format: { success: true, data: { token, user: {...} } }
      const data = response.data;
      const authToken = data.token || data.data?.token;
      const userData = data.user || data.data?.user || data;
      
      // Remove token from userData if it exists
      const { token: _, ...userInfo } = userData;
      
      localStorage.setItem('token', authToken);
      localStorage.setItem('user', JSON.stringify(userInfo));
      
      setToken(authToken);
      setUser(userInfo);
      
      return { success: true };
    } catch (error) {
      console.error('Login error:', error);
      return { 
        success: false, 
        error: error.message || error.response?.data?.error || 'Login failed' 
      };
    }
  };

  const register = async (userData) => {
    try {
      const response = await authAPI.register(userData);
      
      // Handle new response format: { success: true, data: { token, user: {...} } }
      const data = response.data;
      const authToken = data.token || data.data?.token;
      const userInfo = data.user || data.data?.user || data;
      
      // Remove token from userInfo if it exists
      const { token: _, ...cleanUserInfo } = userInfo;
      
      localStorage.setItem('token', authToken);
      localStorage.setItem('user', JSON.stringify(cleanUserInfo));
      
      setToken(authToken);
      setUser(cleanUserInfo);
      
      return { success: true };
    } catch (error) {
      console.error('Register error:', error);
      return { 
        success: false, 
        error: error.message || error.response?.data?.error || 'Registration failed' 
      };
    }
  };

  const logout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    setToken(null);
    setUser(null);
  };

  const value = {
    user,
    token,
    loading,
    login,
    register,
    logout,
    isAuthenticated: !!token
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');
  }
  return context;
};
