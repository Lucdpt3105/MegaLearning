import OpenAI from 'openai';
import { ChatMessage, User } from '../models/index.js';
import dotenv from 'dotenv';

dotenv.config();

class AIService {
  constructor() {
    this.openai = new OpenAI({
      apiKey: process.env.OPENAI_API_KEY || 'dummy-key'
    });
    this.model = process.env.OPENAI_MODEL || 'gpt-3.5-turbo';
    this.maxTokens = parseInt(process.env.OPENAI_MAX_TOKENS) || 500;
  }

  shouldRespond(message) {
    const lowerMessage = message.toLowerCase();
    return (
      lowerMessage.includes('ai') ||
      message.includes('?') ||
      Math.random() < 0.1 // 10% random
    );
  }

  async generateResponse(userMessage, recentMessages = []) {
    try {
      const messages = [
        {
          role: 'system',
          content: 'Bạn là trợ lý AI thông minh trong hệ thống E-Learning MegaLearning. ' +
                  'Hãy trả lời câu hỏi một cách ngắn gọn (2-3 câu), tự nhiên và hữu ích. ' +
                  'Hỗ trợ sinh viên học tập, giải đáp thắc mắc về môn học.'
        }
      ];

      // Add context from recent messages
      if (recentMessages.length > 0) {
        recentMessages.slice(-10).forEach(msg => {
          messages.push({
            role: msg.isAiResponse ? 'assistant' : 'user',
            content: `${msg.user.name}: ${msg.message}`
          });
        });
      }

      // Add current message
      messages.push({
        role: 'user',
        content: userMessage
      });

      const completion = await this.openai.chat.completions.create({
        model: this.model,
        messages: messages,
        max_tokens: this.maxTokens,
        temperature: 0.7
      });

      return completion.choices[0].message.content;
    } catch (error) {
      console.error('OpenAI API error:', error);
      return 'Xin lỗi, tôi không thể trả lời câu hỏi này lúc này. Vui lòng thử lại sau.';
    }
  }

  async generateAndSaveResponse(roomId, userMessage) {
    try {
      // Get recent messages for context
      const recentMessages = await ChatMessage.findAll({
        where: { roomId },
        include: [{
          model: User,
          as: 'user',
          attributes: ['userId', 'name']
        }],
        order: [['createdAt', 'DESC']],
        limit: 20
      });

      // Generate AI response
      const aiResponse = await this.generateResponse(userMessage, recentMessages.reverse());

      // Get AI user
      const aiUser = await User.findOne({ where: { email: 'ai@megalearning.com' } });
      if (!aiUser) {
        console.error('AI user not found');
        return null;
      }

      // Save AI message
      const aiMessage = await ChatMessage.create({
        roomId,
        userId: aiUser.userId,
        message: aiResponse,
        isAiResponse: true
      });

      // Load with user info
      return await ChatMessage.findByPk(aiMessage.messageId, {
        include: [{
          model: User,
          as: 'user',
          attributes: ['userId', 'name', 'email', 'role']
        }]
      });
    } catch (error) {
      console.error('AI generate and save error:', error);
      return null;
    }
  }
}

export const aiService = new AIService();
