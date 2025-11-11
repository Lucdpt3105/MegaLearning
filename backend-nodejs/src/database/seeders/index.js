import bcrypt from 'bcryptjs';
import { User, Subject, ChatRoom, syncDatabase } from '../models/index.js';

const seedDatabase = async () => {
  try {
    console.log('🌱 Seeding database...\n');

    // Sync database
    await syncDatabase();

    // Create demo users
    console.log('Creating users...');
    const hashedPassword = await bcrypt.hash('123456', 10);

    const users = await Promise.all([
      User.findOrCreate({
        where: { email: 'admin@test.com' },
        defaults: {
          password: hashedPassword,
          name: 'Admin User',
          role: 'ADMIN',
          active: true
        }
      }),
      User.findOrCreate({
        where: { email: 'teacher@test.com' },
        defaults: {
          password: hashedPassword,
          name: 'John Doe',
          role: 'TEACHER',
          active: true
        }
      }),
      User.findOrCreate({
        where: { email: 'student1@test.com' },
        defaults: {
          password: hashedPassword,
          name: 'Alice Student',
          role: 'STUDENT',
          active: true
        }
      }),
      User.findOrCreate({
        where: { email: 'student2@test.com' },
        defaults: {
          password: hashedPassword,
          name: 'Bob Student',
          role: 'STUDENT',
          active: true
        }
      }),
      User.findOrCreate({
        where: { email: 'student3@test.com' },
        defaults: {
          password: hashedPassword,
          name: 'Charlie Student',
          role: 'STUDENT',
          active: true
        }
      }),
      User.findOrCreate({
        where: { email: 'ai@megalearning.com' },
        defaults: {
          password: hashedPassword,
          name: 'AI Assistant',
          role: 'AI',
          active: true
        }
      })
    ]);

    console.log('✅ Users created\n');

    // Create subjects
    console.log('Creating subjects...');
    await Promise.all([
      Subject.findOrCreate({
        where: { name: 'Java Programming' },
        defaults: {
          description: 'Learn Java from basics to advanced',
          active: true
        }
      }),
      Subject.findOrCreate({
        where: { name: 'Spring Boot' },
        defaults: {
          description: 'Master Spring Boot framework',
          active: true
        }
      }),
      Subject.findOrCreate({
        where: { name: 'React Development' },
        defaults: {
          description: 'Build modern web apps with React',
          active: true
        }
      }),
      Subject.findOrCreate({
        where: { name: 'Node.js & Express' },
        defaults: {
          description: 'Backend development with Node.js',
          active: true
        }
      })
    ]);

    console.log('✅ Subjects created\n');

    // Create chat rooms
    console.log('Creating chat rooms...');
    const [generalRoom] = await ChatRoom.findOrCreate({
      where: { name: 'General Discussion' },
      defaults: {
        description: 'Chat about anything related to learning',
        type: 'GROUP',
        isAiEnabled: true,
        active: true
      }
    });

    const [javaRoom] = await ChatRoom.findOrCreate({
      where: { name: 'Java Study Group' },
      defaults: {
        description: 'Discuss Java programming topics',
        type: 'STUDY_GROUP',
        isAiEnabled: true,
        active: true
      }
    });

    const [reactRoom] = await ChatRoom.findOrCreate({
      where: { name: 'React Help' },
      defaults: {
        description: 'Get help with React questions',
        type: 'STUDY_GROUP',
        isAiEnabled: true,
        active: true
      }
    });

    console.log('✅ Chat rooms created\n');

    // Add members to rooms
    console.log('Adding members to rooms...');
    const allUsers = users.map(([user]) => user);
    
    for (const room of [generalRoom, javaRoom, reactRoom]) {
      await room.setMembers(allUsers);
    }

    console.log('✅ Members added\n');

    console.log('========================================');
    console.log('🎉 Database seeded successfully!');
    console.log('========================================\n');
    console.log('📋 Demo Accounts (Password: 123456):\n');
    console.log('  admin@test.com    - Admin');
    console.log('  teacher@test.com  - Teacher');
    console.log('  student1@test.com - Student (Alice)');
    console.log('  student2@test.com - Student (Bob)');
    console.log('  student3@test.com - Student (Charlie)');
    console.log('\n========================================\n');

    process.exit(0);
  } catch (error) {
    console.error('❌ Seeding error:', error);
    process.exit(1);
  }
};

seedDatabase();
