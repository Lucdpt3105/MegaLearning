import React from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Dashboard = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  return (
    <div className="min-h-screen bg-gray-100">
      <nav className="bg-white shadow-lg">
        <div className="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
          <h1 className="text-2xl font-bold text-blue-600">MegaLearning</h1>
          <div className="flex items-center space-x-4">
            <span className="text-gray-700">Welcome, {user?.name}</span>
            <span className="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
              {user?.role}
            </span>
            <button
              onClick={handleLogout}
              className="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
            >
              Logout
            </button>
          </div>
        </div>
      </nav>

      <div className="max-w-7xl mx-auto px-4 py-8">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            onClick={() => navigate('/subjects')}
            className="bg-white p-6 rounded-lg shadow-md hover:shadow-xl cursor-pointer transition-shadow"
          >
            <div className="text-4xl mb-4">📚</div>
            <h3 className="text-xl font-bold text-gray-800 mb-2">Subjects</h3>
            <p className="text-gray-600">Browse all available subjects</p>
          </div>

          <div
            onClick={() => navigate('/chat')}
            className="bg-white p-6 rounded-lg shadow-md hover:shadow-xl cursor-pointer transition-shadow"
          >
            <div className="text-4xl mb-4">💬</div>
            <h3 className="text-xl font-bold text-gray-800 mb-2">Chat</h3>
            <p className="text-gray-600">Chat with AI and classmates</p>
          </div>

          <div
            onClick={() => navigate('/exams')}
            className="bg-white p-6 rounded-lg shadow-md hover:shadow-xl cursor-pointer transition-shadow"
          >
            <div className="text-4xl mb-4">📝</div>
            <h3 className="text-xl font-bold text-gray-800 mb-2">Exams</h3>
            <p className="text-gray-600">Take tests and view results</p>
          </div>
        </div>

        <div className="mt-8 bg-white p-6 rounded-lg shadow-md">
          <h2 className="text-2xl font-bold text-gray-800 mb-4">Recent Activity</h2>
          <p className="text-gray-600">Your learning activities will appear here.</p>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
