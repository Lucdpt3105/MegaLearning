import React, { useState, useEffect, useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import { chatAPI } from '../services/api';
import { useAuth } from '../context/AuthContext';
import websocketService from '../services/websocket';

const Chat = () => {
  const [rooms, setRooms] = useState([]);
  const [selectedRoom, setSelectedRoom] = useState(null);
  const [messages, setMessages] = useState([]);
  const [newMessage, setNewMessage] = useState('');
  const [loading, setLoading] = useState(false);
  const { user } = useAuth();
  const navigate = useNavigate();
  const messagesEndRef = useRef(null);
  const subscriptionRef = useRef(null);

  useEffect(() => {
    loadRooms();
    
    // Connect WebSocket
    websocketService.connect(
      () => console.log('WebSocket connected'),
      (error) => console.error('WebSocket error:', error)
    );

    return () => {
      if (subscriptionRef.current) {
        subscriptionRef.current.unsubscribe();
      }
      websocketService.disconnect();
    };
  }, []);

  useEffect(() => {
    if (selectedRoom) {
      loadMessages(selectedRoom.roomId);
      
      // Subscribe to room updates
      if (subscriptionRef.current) {
        subscriptionRef.current.unsubscribe();
      }
      
      subscriptionRef.current = websocketService.subscribeToRoom(
        selectedRoom.roomId,
        (message) => {
          setMessages(prev => [message, ...prev]);
        }
      );
    }
  }, [selectedRoom]);

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  const loadRooms = async () => {
    try {
      const response = await chatAPI.getRooms();
      setRooms(response.data);
    } catch (error) {
      console.error('Error loading rooms:', error);
    }
  };

  const loadMessages = async (roomId) => {
    try {
      setLoading(true);
      const response = await chatAPI.getRoomMessages(roomId);
      setMessages(response.data.reverse());
    } catch (error) {
      console.error('Error loading messages:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleSendMessage = async (e) => {
    e.preventDefault();
    if (!newMessage.trim() || !selectedRoom) return;

    try {
      await chatAPI.sendMessage(selectedRoom.roomId, newMessage);
      setNewMessage('');
    } catch (error) {
      console.error('Error sending message:', error);
    }
  };

  return (
    <div className="h-screen flex flex-col bg-gray-100">
      {/* Header */}
      <div className="bg-white shadow-md p-4 flex justify-between items-center">
        <h1 className="text-2xl font-bold text-blue-600">Chat</h1>
        <button
          onClick={() => navigate('/dashboard')}
          className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
        >
          Back to Dashboard
        </button>
      </div>

      <div className="flex-1 flex overflow-hidden">
        {/* Rooms List */}
        <div className="w-1/4 bg-white border-r overflow-y-auto">
          <div className="p-4 border-b">
            <h2 className="font-bold text-lg">Rooms</h2>
          </div>
          {rooms.map(room => (
            <div
              key={room.roomId}
              onClick={() => setSelectedRoom(room)}
              className={`p-4 cursor-pointer hover:bg-gray-100 border-b ${
                selectedRoom?.roomId === room.roomId ? 'bg-blue-50' : ''
              }`}
            >
              <div className="font-semibold">{room.name}</div>
              <div className="text-sm text-gray-600">{room.type}</div>
              {room.isAiEnabled && (
                <span className="text-xs bg-green-100 text-green-800 px-2 py-1 rounded mt-1 inline-block">
                  AI Enabled
                </span>
              )}
            </div>
          ))}
        </div>

        {/* Chat Area */}
        <div className="flex-1 flex flex-col">
          {selectedRoom ? (
            <>
              {/* Room Header */}
              <div className="bg-white p-4 border-b">
                <h3 className="font-bold text-lg">{selectedRoom.name}</h3>
                <p className="text-sm text-gray-600">{selectedRoom.description}</p>
              </div>

              {/* Messages */}
              <div className="flex-1 overflow-y-auto p-4 space-y-4">
                {loading ? (
                  <div className="text-center text-gray-500">Loading messages...</div>
                ) : messages.length === 0 ? (
                  <div className="text-center text-gray-500">No messages yet</div>
                ) : (
                  messages.map((msg, index) => (
                    <div
                      key={index}
                      className={`flex ${msg.user.userId === user.userId ? 'justify-end' : 'justify-start'}`}
                    >
                      <div
                        className={`max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${
                          msg.isAiResponse
                            ? 'bg-green-100 text-green-900'
                            : msg.user.userId === user.userId
                            ? 'bg-blue-500 text-white'
                            : 'bg-gray-300 text-gray-900'
                        }`}
                      >
                        <div className="font-semibold text-sm mb-1">
                          {msg.isAiResponse ? '🤖 AI' : msg.user.name}
                        </div>
                        <div>{msg.message}</div>
                        <div className="text-xs mt-1 opacity-75">
                          {new Date(msg.createdAt).toLocaleTimeString()}
                        </div>
                      </div>
                    </div>
                  ))
                )}
                <div ref={messagesEndRef} />
              </div>

              {/* Message Input */}
              <form onSubmit={handleSendMessage} className="bg-white p-4 border-t">
                <div className="flex space-x-2">
                  <input
                    type="text"
                    value={newMessage}
                    onChange={(e) => setNewMessage(e.target.value)}
                    placeholder="Type a message..."
                    className="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  <button
                    type="submit"
                    className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
                  >
                    Send
                  </button>
                </div>
              </form>
            </>
          ) : (
            <div className="flex items-center justify-center h-full text-gray-500">
              Select a room to start chatting
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Chat;
