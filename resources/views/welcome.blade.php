<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MegaLearning - Nền tảng học tập hiện đại</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            animation: countUp 2s ease-out;
        }
        
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 1s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/90 backdrop-blur-md shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.svg') }}" alt="MegaLearning Logo" class="h-12 w-auto">
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-purple-600 transition">Tính năng</a>
                    <a href="#stats" class="text-gray-600 hover:text-purple-600 transition">Thống kê</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-purple-600 transition">Đánh giá</a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-purple-600 transition">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="hero-gradient text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
                        Bắt đầu ngay
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <button class="md:hidden text-gray-600">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden bg-white">
        
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="fade-in">
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Học tập thông minh với
                        <span class="gradient-text">AI</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Nền tảng học trực tuyến hiện đại với AI hỗ trợ, quiz tương tác và cộng đồng học tập sôi động.
                    </p>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="hero-gradient text-white px-8 py-4 rounded-lg font-semibold hover:opacity-90 transition inline-flex items-center">
                            Khám phá ngay
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        <a href="#features" class="bg-white text-purple-600 border-2 border-purple-600 px-8 py-4 rounded-lg font-semibold hover:bg-purple-50 transition">
                            Tìm hiểu thêm
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-8 mt-12">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 stat-number" data-target="1000">0</div>
                            <div class="text-gray-600">Học viên</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900 stat-number" data-target="500">0</div>
                            <div class="text-gray-600">Lớp học</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900 stat-number" data-target="50">0</div>
                            <div class="text-gray-600">Giảng viên</div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <img id="heroImage" 
                         src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&q=80" 
                         alt="Learning" 
                         class="rounded-2xl shadow-2xl w-full object-cover h-[500px]">
                    
                    <!-- Floating Card -->
                    <div class="absolute -bottom-8 -left-8 bg-white p-6 rounded-xl shadow-xl hidden md:block">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">98% thành công</div>
                                <div class="text-sm text-gray-500">Học viên đạt mục tiêu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Tính năng nổi bật</h2>
                <p class="text-xl text-gray-600">Mọi thứ bạn cần cho hành trình học tập</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-hover bg-gradient-to-br from-purple-50 to-white p-8 rounded-2xl border border-purple-100">
                    <div class="w-16 h-16 hero-gradient rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-robot text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">AI Chat Assistant</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Trò chuyện với Gemini AI để giải đáp thắc mắc học tập 24/7. Hỗ trợ đa ngôn ngữ và giải thích chi tiết.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="card-hover bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl border border-blue-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-clipboard-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Quiz tương tác</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Hàng nghìn câu hỏi trắc nghiệm, tự động chấm điểm và phân tích chi tiết kết quả học tập.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="card-hover bg-gradient-to-br from-green-50 to-white p-8 rounded-2xl border border-green-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Cộng đồng học tập</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Diễn đàn thảo luận sôi động, chia sẻ kiến thức và học hỏi từ cộng đồng học viên.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="card-hover bg-gradient-to-br from-yellow-50 to-white p-8 rounded-2xl border border-yellow-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Theo dõi tiến độ</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Dashboard trực quan hiển thị tiến độ học tập, điểm số và thống kê chi tiết.
                    </p>
                </div>
                
                <!-- Feature 5 -->
                <div class="card-hover bg-gradient-to-br from-pink-50 to-white p-8 rounded-2xl border border-pink-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-video text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Video call học tập</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Tham gia lớp học trực tuyến với video call chất lượng cao và tương tác real-time.
                    </p>
                </div>
                
                <!-- Feature 6 -->
                <div class="card-hover bg-gradient-to-br from-indigo-50 to-white p-8 rounded-2xl border border-indigo-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-certificate text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Chứng chỉ</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Nhận chứng chỉ hoàn thành lớp học được công nhận và chia sẻ thành tích.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section with Parallax -->
    <section id="stats" class="py-32 relative" style="background-image: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1600&q=80'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-gradient-to-r from-purple-900/90 to-indigo-900/90"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Con số ấn tượng</h2>
                <p class="text-xl text-purple-200">Được tin tưởng bởi hàng nghìn học viên</p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8" id="statsContainer">
                <div class="text-center">
                    <div class="text-5xl font-bold text-white mb-2 stat-number" data-target="5000">0</div>
                    <div class="text-purple-200 text-lg">Học viên đang học</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-white mb-2 stat-number" data-target="1200">0</div>
                    <div class="text-purple-200 text-lg">Lớp học</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-white mb-2 stat-number" data-target="150">0</div>
                    <div class="text-purple-200 text-lg">Giảng viên</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-white mb-2 stat-number" data-target="98">0</div>
                    <div class="text-purple-200 text-lg">% Hài lòng</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Học viên nói gì về chúng tôi</h2>
                <p class="text-xl text-gray-600">Câu chuyện thành công từ cộng đồng</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8" id="testimonialsContainer">
                <!-- Testimonials will be loaded here -->
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 hero-gradient">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Sẵn sàng bắt đầu hành trình học tập?
            </h2>
            <p class="text-xl text-purple-100 mb-8">
                Tham gia cùng hàng nghìn học viên đang học tập mỗi ngày
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition inline-flex items-center">
                    Đăng ký miễn phí
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="{{ route('login') }}" class="bg-purple-700 text-white px-8 py-4 rounded-lg font-semibold hover:bg-purple-800 transition">
                    Đăng nhập ngay
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 hero-gradient rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-white">MegaLearning</span>
                    </div>
                    <p class="text-gray-400">Nền tảng học tập hiện đại với công nghệ AI</p>
                </div>
                
                <div>
                    <h3 class="text-white font-semibold mb-4">Sản phẩm</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition">Lớp học</a></li>
                        <li><a href="#" class="hover:text-white transition">Quiz</a></li>
                        <li><a href="#" class="hover:text-white transition">AI Chat</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-white font-semibold mb-4">Công ty</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition">Về chúng tôi</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Liên hệ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-white font-semibold mb-4">Theo dõi</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2025 MegaLearning. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Counter Animation
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 16);
        }

        // Intersection Observer for animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('stat-number')) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.stat-number').forEach(stat => {
            observer.observe(stat);
        });

        // Load random hero image from Unsplash
        const heroImages = [
            'photo-1522202176988-66273c2fd55f', // Students studying
            'photo-1523240795612-9a054b0db644', // Group study
            'photo-1497633762265-9d179a990aa6', // Books
            'photo-1524178232363-1fb2b075b655', // Laptop learning
            'photo-1434030216411-0b793f4b4173', // Note taking
        ];
        
        const randomImage = heroImages[Math.floor(Math.random() * heroImages.length)];
        document.getElementById('heroImage').src = `https://images.unsplash.com/${randomImage}?w=800&q=80`;

        // Load testimonials from JSONPlaceholder
        async function loadTestimonials() {
            try {
                const response = await fetch('https://jsonplaceholder.typicode.com/users?_limit=3');
                const users = await response.json();
                
                const container = document.getElementById('testimonialsContainer');
                
                const testimonialTexts = [
                    'Platform này đã giúp tôi nâng cao kiến thức một cách đáng kể. Giao diện thân thiện và AI hỗ trợ rất tốt!',
                    'Quiz tương tác và hệ thống theo dõi tiến độ giúp tôi luôn có động lực học tập. Rất đáng để thử!',
                    'Cộng đồng học tập sôi động, giảng viên nhiệt tình. Tôi đã hoàn thành 10 lớp học trong 3 tháng!'
                ];
                
                users.forEach((user, index) => {
                    const testimonial = document.createElement('div');
                    testimonial.className = 'bg-white p-8 rounded-2xl shadow-lg card-hover';
                    testimonial.innerHTML = `
                        <div class="flex items-center mb-4">
                            <img src="https://ui-avatars.com/api/?name=${user.name}&background=random" 
                                 alt="${user.name}" 
                                 class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <div class="font-semibold text-gray-900">${user.name}</div>
                                <div class="text-sm text-gray-500">${user.company.name}</div>
                            </div>
                        </div>
                        <div class="text-yellow-400 mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-600 leading-relaxed">${testimonialTexts[index]}</p>
                    `;
                    container.appendChild(testimonial);
                });
            } catch (error) {
                console.error('Error loading testimonials:', error);
            }
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadTestimonials();
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
