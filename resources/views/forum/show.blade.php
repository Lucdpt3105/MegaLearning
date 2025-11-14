@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-8">
	<div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
		<header class="mb-4 border-b border-gray-100 pb-4 flex items-start justify-between gap-6">
			<div class="flex-1">
				<h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $question->title }}</h1>
				<div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500">
					<span>👤 {{ optional($question->user)->name ?? 'Unknown' }}</span>
					<span>🕓 Created {{ $question->created_at->diffForHumans() }}</span>
					@if($question->updated_at && $question->updated_at->ne($question->created_at))
						<span>🔄 Updated {{ $question->updated_at->diffForHumans() }}</span>
					@endif
				</div>
			</div>
			<div class="flex items-center gap-3">
				<div id="vote-box"
					 data-up-url="{{ route('forum.vote.up', $question->getKey()) }}"
					 data-down-url="{{ route('forum.vote.down', $question->getKey()) }}"
					 data-csrf="{{ csrf_token() }}"
					 data-my-vote="{{ $my_vote ?? 0 }}"
					 class="flex flex-col items-center justify-center rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-sm font-medium text-gray-700 shadow-sm">
					<button id="vote-up" type="button" class="inline-flex items-center rounded px-2 py-1 text-xs font-semibold shadow focus:outline-none focus:ring-2" title="Upvote">▲</button>
					<span id="vote-count" class="my-0.5 text-base font-semibold">{{ $votes_sum }}</span>
					<button id="vote-down" type="button" class="inline-flex items-center rounded px-2 py-1 text-xs font-semibold shadow focus:outline-none focus:ring-2" title="Downvote">▼</button>
				</div>
			</div>
		</header>

		<article class="prose max-w-none text-gray-800">
			<p class="whitespace-pre-line">{{ $question->content }}</p>
		</article>

		<div class="mt-4 flex items-center gap-3">
			<a href="{{ route('forum.back') }}" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">Back</a>
			@can('update', $question)
				<a href="{{ route('forum.edit', $question->getKey()) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Edit</a>
			@endcan
		</div>
	</div>
    	<section class="space-y-6">
		<div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
			<h2 class="text-lg font-semibold text-gray-800 mb-4">Add Answer</h2>
			@if(session('success-answer'))
				<div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">{{ session('success-answer') }}</div>
			@endif
			<form method="POST" action="{{ route('forum.answer.store', $question->getKey()) }}" class="space-y-4 js-answer-form" data-parent-id="" id="new-answer-form">
				@csrf
				<div>
					<textarea name="answer_content" rows="4" required placeholder="Nhập câu trả lời của bạn" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('answer_content') }}</textarea>
					@error('answer_content')
						<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>
				<div class="flex justify-end">
					<button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Post Answer</button>
				</div>
			</form>
		</div>
	<section class="space-y-4">
		<h2 class="text-lg font-semibold text-gray-800">Answers ({{ $answers_count }})</h2>
		@if(empty($answersTree))
			<p class="text-sm text-gray-500">No answers yet.</p>
		@else
			<div class="space-y-4" id="answers-wrapper">
				@foreach($answersTree as $node)
					@include('forum._answer', ['node'=>$node])
				@endforeach
			</div>
		@endif
	</section>
</div>
<script>
	(function(){
		const box = document.getElementById('vote-box');
		if(!box) return;
		const upBtn = document.getElementById('vote-up');
		const downBtn = document.getElementById('vote-down');
		const countEl = document.getElementById('vote-count');
		const upUrl = box.getAttribute('data-up-url');
		const downUrl = box.getAttribute('data-down-url');
		const csrf = box.getAttribute('data-csrf');
		let myVote = parseInt(box.getAttribute('data-my-vote') || '0', 10);

		function applyClasses(){
			// reset to gray
			const base = 'inline-flex items-center rounded px-2 py-1 text-xs font-semibold shadow focus:outline-none focus:ring-2';
			upBtn.className = base + ' bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-green-500';
			downBtn.className = base + ' bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-red-500';
			if(myVote === 1){
				upBtn.className = base + ' bg-green-600 text-white hover:bg-green-700 focus:ring-green-500';
			} else if(myVote === -1){
				downBtn.className = base + ' bg-red-600 text-white hover:bg-red-700 focus:ring-red-500';
			}
		}
		applyClasses();

		let busy = false;
		async function sendVote(url){
			if(busy) return;
			busy = true;
			upBtn.disabled = true; downBtn.disabled = true;
			upBtn.classList.add('opacity-60'); downBtn.classList.add('opacity-60');
			try{
				const res = await fetch(url, {
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': csrf,
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json'
					}
				});
				if(!res.ok){ throw new Error('Vote failed'); }
				const data = await res.json();
				if(typeof data.votes_sum !== 'undefined'){
					countEl.textContent = data.votes_sum;
				}
				if(typeof data.my_vote !== 'undefined'){
					myVote = parseInt(data.my_vote, 10);
					box.setAttribute('data-my-vote', String(myVote));
					applyClasses();
				}
			}catch(e){
				console.error(e);
			}finally{
				busy = false;
				upBtn.disabled = false; downBtn.disabled = false;
				upBtn.classList.remove('opacity-60'); downBtn.classList.remove('opacity-60');
			}
		}

		upBtn?.addEventListener('click', ()=> sendVote(upUrl));
		downBtn?.addEventListener('click', ()=> sendVote(downUrl));
	})();

// Answer vote + reply interactions
(function(){
	const csrf = document.querySelector('[data-answer-vote-box]')?.getAttribute('data-csrf');
	if(!csrf) return;

	function applyAnswerClasses(container){
		const upBtn = container.querySelector('.answer-vote-up');
		const downBtn = container.querySelector('.answer-vote-down');
		const myVote = parseInt(container.getAttribute('data-my-vote')||'0',10);
		const base = 'answer-vote-up inline-flex items-center rounded px-2 py-1 text-xs font-semibold shadow focus:outline-none focus:ring-2';
		const baseDown = 'answer-vote-down inline-flex items-center rounded px-2 py-1 text-xs font-semibold shadow focus:outline-none focus:ring-2';
		upBtn.className = base + ' bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-green-500';
		downBtn.className = baseDown + ' bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-red-500';
		if(myVote===1){
			upBtn.className = base + ' bg-green-600 text-white hover:bg-green-700 focus:ring-green-500';
		} else if(myVote===-1){
			downBtn.className = baseDown + ' bg-red-600 text-white hover:bg-red-700 focus:ring-red-500';
		}
	}

	document.querySelectorAll('[data-answer-vote-box]').forEach(applyAnswerClasses);

	async function sendAnswerVote(container, url){
		const upBtn = container.querySelector('.answer-vote-up');
		const downBtn = container.querySelector('.answer-vote-down');
		const countEl = container.querySelector('.answer-vote-count');
		if(container.dataset.busy==='1') return;
		container.dataset.busy='1';
		upBtn.disabled=true; downBtn.disabled=true; upBtn.classList.add('opacity-60'); downBtn.classList.add('opacity-60');
		try {
			const res = await fetch(url, {method:'POST', headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}});
			if(!res.ok) throw new Error('Vote failed');
			const data = await res.json();
			if(typeof data.votes_sum!=='undefined'){ countEl.textContent = data.votes_sum; }
			if(typeof data.my_vote!=='undefined'){ container.setAttribute('data-my-vote', data.my_vote); applyAnswerClasses(container); }
		} catch(e){ console.error(e); }
		finally {
			container.dataset.busy='0';
			upBtn.disabled=false; downBtn.disabled=false; upBtn.classList.remove('opacity-60'); downBtn.classList.remove('opacity-60');
		}
	}

	document.addEventListener('click', function(e){
		if(e.target.classList.contains('answer-vote-up')){
			const c = e.target.closest('[data-answer-vote-box]');
			sendAnswerVote(c, c.getAttribute('data-up-url'));
		} else if(e.target.classList.contains('answer-vote-down')){
			const c = e.target.closest('[data-answer-vote-box]');
			sendAnswerVote(c, c.getAttribute('data-down-url'));
		} else if(e.target.classList.contains('toggle-reply')){
			const targetSel = e.target.getAttribute('data-target');
			const formEl = document.querySelector(targetSel);
			if(formEl){ formEl.classList.toggle('hidden'); }
		} else if(e.target.classList.contains('cancel-reply')){
			const formEl = e.target.closest('#'+e.target.closest('[id]').id);
			if(formEl){ formEl.classList.add('hidden'); }
		}
	});
})();
</script>
<script>
// AJAX create & delete answers (no reload)
(function(){
	const wrapper = document.getElementById('answers-wrapper');
	if(!wrapper) return;
	const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('[data-csrf]')?.getAttribute('data-csrf');

	async function submitAnswer(form){
		const url = form.getAttribute('action');
		const fd = new FormData(form);
		try {
			const res = await fetch(url, { method:'POST', headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}, body: fd });
			if(!res.ok) throw new Error('Failed');
			const data = await res.json();
			if(data.html){ insertAnswerHtml(data); }
			form.reset();
			if(form.dataset.parentId){ const replyWrap = form.closest('#reply-form-'+form.dataset.parentId); replyWrap && replyWrap.classList.add('hidden'); }
		} catch(e){ console.error(e); alert('Post failed'); }
	}

	function insertAnswerHtml(data){
		const temp = document.createElement('div'); temp.innerHTML = data.html.trim();
		const answerEl = temp.firstElementChild; if(!answerEl) return;
		if(!data.parent_id){
			wrapper.appendChild(answerEl);
		} else {
			const parentEl = wrapper.querySelector('[data-answer-id="'+data.parent_id+'"]');
			if(parentEl){
				let childrenWrap = parentEl.querySelector('.children-wrapper');
				if(!childrenWrap){
					childrenWrap = document.createElement('div');
					childrenWrap.className = 'mt-3 space-y-4 children-wrapper';
					parentEl.appendChild(childrenWrap);
				}
				childrenWrap.appendChild(answerEl);
			} else {
				wrapper.appendChild(answerEl);
			}
		}
	}

	document.addEventListener('submit', function(e){
		const form = e.target;
		if(form.classList.contains('js-answer-form')){
			e.preventDefault();
			const ta = form.querySelector('textarea[name="answer_content"]');
			if(!ta || ta.value.trim().length===0){ alert('Content required'); return; }
			submitAnswer(form);
		}
	});

	document.addEventListener('submit', function(e){
		const form = e.target;
		if(form.matches('form[action*="/answers/"]') && form.querySelector('input[name="_method"][value="DELETE"]')){
			e.preventDefault();
			if(!confirm('Delete this answer and its replies?')) return;
			const url = form.getAttribute('action');
			const fd = new FormData(form);
			fetch(url, { method:'POST', headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}, body: fd })
				.then(r=>r.json())
				.then(data=>{
					if(data.deleted){
						const el = wrapper.querySelector('[data-answer-id="'+data.answer_id+'"]');
						if(el){ el.remove(); }
					}
				})
				.catch(err=>console.error(err));
		}
	});
})();
</script>
@endsection
