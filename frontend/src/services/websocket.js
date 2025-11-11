import { io } from 'socket.io-client';

const WS_URL = import.meta.env.VITE_WS_URL || 'http://localhost:8080';

class WebSocketService {
  constructor() {
    this.socket = null;
    this.connected = false;
  }

  connect(token, onConnected, onError) {
    if (this.socket && this.connected) {
      console.log('Socket already connected');
      return;
    }

    this.socket = io(WS_URL, {
      auth: { token },
      reconnection: true,
      reconnectionDelay: 5000,
      reconnectionAttempts: 5,
      transports: ['websocket', 'polling']
    });

    this.socket.on('connect', () => {
      this.connected = true;
      console.log('✅ Socket.IO Connected');
      if (onConnected) onConnected();
    });

    this.socket.on('disconnect', (reason) => {
      this.connected = false;
      console.log('❌ Socket.IO Disconnected:', reason);
    });

    this.socket.on('connect_error', (error) => {
      console.error('❌ Socket.IO Connection Error:', error.message);
      this.connected = false;
      if (onError) onError(error);
    });

    this.socket.on('error', (error) => {
      console.error('❌ Socket Error:', error);
      if (onError) onError(error);
    });
  }

  disconnect() {
    if (this.socket) {
      this.socket.disconnect();
      this.socket = null;
      this.connected = false;
      console.log('Socket disconnected');
    }
  }

  joinRoom(roomId) {
    if (this.socket && this.connected) {
      this.socket.emit('join-room', roomId);
      console.log(`Joined room: ${roomId}`);
    } else {
      console.warn('Cannot join room: Socket not connected');
    }
  }

  leaveRoom(roomId) {
    if (this.socket && this.connected) {
      this.socket.emit('leave-room', roomId);
      console.log(`Left room: ${roomId}`);
    }
  }

  onNewMessage(callback) {
    if (this.socket) {
      this.socket.on('new-message', callback);
    }
  }

  offNewMessage() {
    if (this.socket) {
      this.socket.off('new-message');
    }
  }

  sendMessage(roomId, message) {
    if (this.socket && this.connected) {
      this.socket.emit('send-message', { roomId, message });
    } else {
      console.warn('Cannot send message: Socket not connected');
      throw new Error('Socket not connected');
    }
  }

  isConnected() {
    return this.connected;
  }
}

export default new WebSocketService();
