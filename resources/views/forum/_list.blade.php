@forelse($questions as $q)
<article class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
    <!-- Post Header -->
    <div class="p-5">
        <div class="flex items-start gap-3 mb-4">
            @php
                $userSeed = $q->user_id ?? 1;
                $userName = optional($q->user)->name ?? 'Unknown User';
                $userEmail = optional($q->user)->email ?? '';
            @endphp
            <img src="https://randomuser.me/api/portraits/{{ $userSeed % 2 == 0 ? 'women' : 'men' }}/{{ $userSeed }}.jpg" 
                 alt="{{ $userName }}" 
                 onclick="showUserProfile({{ $userSeed }}, '{{ $userName }}', '{{ $userEmail }}')"
                 class="w-12 h-12 rounded-full shrink-0 ring-2 ring-gray-100 cursor-pointer hover:ring-indigo-500 transition object-cover">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <h3 class="font-semibold text-gray-900 cursor-pointer hover:text-indigo-600" 
                        onclick="showUserProfile({{ $userSeed }}, '{{ $userName }}', '{{ $userEmail }}')">
                        {{ $userName }}
                    </h3>
                    <span class="text-gray-400">·</span>
                    <span class="text-sm text-gray-500">{{ $q->created_at->diffForHumans() }}</span>
                    @if($q->updated_at && $q->updated_at != $q->created_at)
                        <span class="text-xs text-gray-400 italic">(edited)</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500">Posted an update</p>
            </div>
            <div class="relative">
                <button class="p-2 hover:bg-gray-100 rounded-full transition">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Post Content -->
        <div class="mb-4">
            <h2 class="text-lg font-bold text-gray-900 mb-2">
                <a href="{{ route('forum.show', $q->forum_question_id) }}" class="hover:text-indigo-600">
                    {{ $q->title }}
                </a>
            </h2>
            <p class="text-gray-700 leading-relaxed">
                {{ \Illuminate\Support\Str::limit($q->content, 280) }}
            </p>
            @if(strlen($q->content) > 280)
                <a href="{{ route('forum.show', $q->forum_question_id) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium mt-2 inline-block">
                    Read more...
                </a>
            @endif
            
            <!-- Post Image -->
            @if($q->image_path)
                <div class="mt-3 rounded-xl overflow-hidden border border-gray-200">
                    <img src="{{ asset($q->image_path) }}" 
                         alt="Post image" 
                         class="w-full max-h-96 object-cover cursor-pointer hover:opacity-95 transition"
                         onclick="window.open('{{ asset($q->image_path) }}', '_blank')">
                </div>
            @endif
            
            <!-- Post Tags -->
            @if($q->tags && count($q->tags) > 0)
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($q->tags as $tag)
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full hover:bg-indigo-100 transition cursor-pointer">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Interaction Stats -->
        <div class="flex items-center gap-1 py-3 border-y border-gray-100" id="stats-{{ $q->forum_question_id }}">
            <div class="flex items-center gap-1 text-sm text-gray-500">
                <span class="flex items-center votes-display-{{ $q->forum_question_id }}">
                    @if($q->votes_sum > 0)
                        <svg class="w-4 h-4 text-blue-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                        </svg>
                        <span class="votes-count-{{ $q->forum_question_id }}">{{ $q->votes_sum }}</span>
                    @else
                        <span class="votes-count-{{ $q->forum_question_id }}">0</span>
                    @endif
                    <span class="ml-1">{{ abs($q->votes_sum) == 1 ? 'Like' : 'Likes' }}</span>
                </span>
            </div>
            <span class="text-gray-300">·</span>
            <span class="text-sm text-gray-500 comments-count-{{ $q->forum_question_id }}">{{ $q->answers_count ?? 0 }} {{ ($q->answers_count ?? 0) == 1 ? 'Comment' : 'Comments' }}</span>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-3">
            <div class="flex items-center gap-2">
                @php
                    $myVote = $q->votes()->where('user_id', auth()->id())->whereNull('forum_answer_id')->first();
                    $isLiked = $myVote && $myVote->value == 1;
                @endphp
                <button onclick="toggleLike({{ $q->forum_question_id }})" 
                        id="like-btn-{{ $q->forum_question_id }}"
                        class="flex items-center gap-2 px-4 py-2 hover:bg-blue-50 rounded-lg transition {{ $isLiked ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">
                    <svg class="w-5 h-5" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="text-sm font-medium">{{ $isLiked ? 'Liked' : 'Like' }}</span>
                </button>
                <button onclick="toggleCommentForm({{ $q->forum_question_id }})" class="flex items-center gap-2 px-4 py-2 hover:bg-green-50 rounded-lg transition text-gray-600 hover:text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span class="text-sm font-medium">Comment</span>
                </button>
                <button onclick="sharePost({{ $q->forum_question_id }}, '{{ addslashes($q->title) }}')" class="flex items-center gap-2 px-4 py-2 hover:bg-purple-50 rounded-lg transition text-gray-600 hover:text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    <span class="text-sm font-medium">Share</span>
                </button>
            </div>
            
            @if(auth()->check() && (auth()->id() === $q->user_id))
            <div class="flex items-center gap-2">
                @can('update', $q)
                    <a href="{{ route('forum.edit', $q->forum_question_id) }}" class="px-3 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition">
                        Edit
                    </a>
                @endcan
                @can('delete', $q)
                    <form action="{{ route('forum.destroy', $q->forum_question_id) }}" method="POST" onsubmit="return confirm('Delete this post?')" class="inline">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition">
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
            @endif
        </div>
        
        <!-- Comments Section -->
        <div id="comments-section-{{ $q->forum_question_id }}" class="hidden border-t border-gray-100 pt-4 mt-3">
            <div id="comments-list-{{ $q->forum_question_id }}" class="space-y-3 mb-3">
                @foreach($q->answers()->whereNull('parent_id')->latest()->take(3)->get() as $answer)
                    <div class="flex gap-2">
                        <img src="https://randomuser.me/api/portraits/{{ $answer->user_id % 2 == 0 ? 'women' : 'men' }}/{{ $answer->user_id }}.jpg" 
                             alt="{{ $answer->user->name }}" 
                             class="w-8 h-8 rounded-full shrink-0 object-cover">
                        <div class="flex-1 bg-gray-50 rounded-lg px-3 py-2">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900">{{ $answer->user->name }}</span>
                                <span class="text-xs text-gray-500">{{ $answer->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-700">{{ $answer->answer_content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($q->answers_count > 3)
                <a href="{{ route('forum.show', $q->forum_question_id) }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                    View all {{ $q->answers_count }} comments
                </a>
            @endif
        </div>
        
        <!-- Quick Comment Form (Hidden by default) -->
        <div id="comment-form-{{ $q->forum_question_id }}" class="hidden border-t border-gray-100 pt-4 mt-3">
            <form onsubmit="submitQuickComment(event, {{ $q->forum_question_id }})" class="flex gap-3">
                @csrf
                <img src="https://randomuser.me/api/portraits/{{ auth()->id() % 2 == 0 ? 'women' : 'men' }}/{{ auth()->id() }}.jpg" 
                     alt="{{ auth()->user()->name }}" 
                     class="w-10 h-10 rounded-full shrink-0 ring-2 ring-gray-100 object-cover">
                <div class="flex-1">
                    <textarea name="answer_content" 
                              id="quick-comment-{{ $q->forum_question_id }}"
                              rows="2" 
                              placeholder="Write a comment..." 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                              required></textarea>
                    <div class="flex items-center justify-between mt-2">
                        <button type="button" 
                                onclick="cancelComment({{ $q->forum_question_id }})"
                                class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition">
                            Post Comment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</article>
@empty
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center">
    <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
    </svg>
    <h3 class="text-lg font-semibold text-gray-900 mb-2">No posts yet</h3>
    <p class="text-gray-500 mb-4">Be the first to share something with the community!</p>
    @can('create', App\Models\ForumQuestion::class)
        <a href="{{ route('forum.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create First Post
        </a>
    @endcan
</div>
@endforelse

@if(method_exists($questions, 'links'))
    <div class="mt-6">
        {{ $questions->appends(request()->query())->links() }}
    </div>
@endif
