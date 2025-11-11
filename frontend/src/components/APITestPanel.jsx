import React, { useState, useEffect } from 'react';
import { subjectAPI, chatAPI, authAPI } from '../services/api';

const APITestPanel = () => {
  const [results, setResults] = useState({});
  const [loading, setLoading] = useState({});
  const [token, setToken] = useState(localStorage.getItem('token'));

  const addResult = (test, data) => {
    setResults(prev => ({
      ...prev,
      [test]: data
    }));
  };

  const setLoadingState = (test, state) => {
    setLoading(prev => ({
      ...prev,
      [test]: state
    }));
  };

  const testAPI = async (testName, apiCall) => {
    setLoadingState(testName, true);
    try {
      const response = await apiCall();
      addResult(testName, {
        success: true,
        data: response.data,
        message: 'Success!'
      });
    } catch (error) {
      addResult(testName, {
        success: false,
        error: error.message || 'Error occurred',
        details: error
      });
    } finally {
      setLoadingState(testName, false);
    }
  };

  const tests = [
    {
      name: 'Health Check',
      description: 'Test backend is running',
      action: () => testAPI('health', () => 
        fetch('http://localhost:8080/api/health').then(r => r.json())
      ),
      needsAuth: false
    },
    {
      name: 'Get Profile',
      description: 'Get current user profile',
      action: () => testAPI('profile', authAPI.getProfile),
      needsAuth: true
    },
    {
      name: 'Get Subjects',
      description: 'Fetch all subjects',
      action: () => testAPI('subjects', subjectAPI.getAll),
      needsAuth: true
    },
    {
      name: 'Get Chat Rooms',
      description: 'Fetch user chat rooms',
      action: () => testAPI('rooms', chatAPI.getRooms),
      needsAuth: true
    }
  ];

  return (
    <div className="p-6 max-w-6xl mx-auto">
      <div className="bg-white rounded-lg shadow-lg p-6">
        <h1 className="text-2xl font-bold mb-4 text-gray-800">
          🧪 API Connection Test Panel
        </h1>
        
        <div className="mb-6 p-4 bg-blue-50 rounded border border-blue-200">
          <p className="text-sm text-gray-700">
            <strong>Backend URL:</strong> http://localhost:8080/api
          </p>
          <p className="text-sm text-gray-700 mt-2">
            <strong>Auth Status:</strong> {token ? 
              <span className="text-green-600">✓ Authenticated</span> : 
              <span className="text-red-600">✗ Not authenticated</span>
            }
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          {tests.map((test) => (
            <div key={test.name} className="border rounded p-4">
              <h3 className="font-semibold text-lg mb-2">{test.name}</h3>
              <p className="text-sm text-gray-600 mb-3">{test.description}</p>
              
              <button
                onClick={test.action}
                disabled={loading[test.name] || (test.needsAuth && !token)}
                className={`w-full py-2 px-4 rounded font-medium transition ${
                  loading[test.name]
                    ? 'bg-gray-400 cursor-wait'
                    : test.needsAuth && !token
                    ? 'bg-gray-300 cursor-not-allowed'
                    : 'bg-blue-500 hover:bg-blue-600 text-white'
                }`}
              >
                {loading[test.name] ? 'Testing...' : 'Run Test'}
              </button>

              {test.needsAuth && !token && (
                <p className="text-xs text-red-500 mt-2">
                  ⚠️ Requires authentication - please login first
                </p>
              )}

              {results[test.name] && (
                <div className={`mt-3 p-3 rounded text-sm ${
                  results[test.name].success 
                    ? 'bg-green-50 border border-green-200' 
                    : 'bg-red-50 border border-red-200'
                }`}>
                  <p className={`font-semibold ${
                    results[test.name].success ? 'text-green-700' : 'text-red-700'
                  }`}>
                    {results[test.name].success ? '✓ Success' : '✗ Failed'}
                  </p>
                  <pre className="mt-2 text-xs overflow-auto max-h-32">
                    {JSON.stringify(
                      results[test.name].data || results[test.name].error, 
                      null, 
                      2
                    )}
                  </pre>
                </div>
              )}
            </div>
          ))}
        </div>

        <div className="border-t pt-4">
          <h3 className="font-semibold mb-3">Test Results Summary</h3>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div className="p-3 bg-gray-100 rounded">
              <div className="text-2xl font-bold text-blue-600">
                {Object.keys(results).length}
              </div>
              <div className="text-xs text-gray-600">Total Tests</div>
            </div>
            <div className="p-3 bg-green-100 rounded">
              <div className="text-2xl font-bold text-green-600">
                {Object.values(results).filter(r => r.success).length}
              </div>
              <div className="text-xs text-gray-600">Passed</div>
            </div>
            <div className="p-3 bg-red-100 rounded">
              <div className="text-2xl font-bold text-red-600">
                {Object.values(results).filter(r => !r.success).length}
              </div>
              <div className="text-xs text-gray-600">Failed</div>
            </div>
            <div className="p-3 bg-yellow-100 rounded">
              <div className="text-2xl font-bold text-yellow-600">
                {Object.keys(loading).filter(k => loading[k]).length}
              </div>
              <div className="text-xs text-gray-600">Running</div>
            </div>
          </div>
        </div>

        <div className="mt-6 p-4 bg-gray-50 rounded">
          <h4 className="font-semibold mb-2">Quick Tips:</h4>
          <ul className="text-sm text-gray-700 space-y-1">
            <li>• Backend phải chạy tại port 8080</li>
            <li>• Login trước để test các API cần authentication</li>
            <li>• Kiểm tra Console (F12) để xem chi tiết lỗi</li>
            <li>• Database phải có dữ liệu (chạy seeder)</li>
          </ul>
        </div>
      </div>
    </div>
  );
};

export default APITestPanel;
