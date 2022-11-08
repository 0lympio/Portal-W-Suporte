@props(['comment', 'nivel'])
<div class="flex {{ $comment->comment_id ? 'm-l-' . $nivel : '' }}">
    <p class="text-gray-600 mb-4">{{ $comment->user->name }} {{ $comment->user->last_name }} •
        {{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:i') }}
    </p>
    @can('comments.destroy')
        <div>
            <button @click="deleteComment=true; commentId = {{ $comment->id }}" class="fa-solid fa-trash text-red ml-2"
                title="Remover"></button>
            @if ($nivel !== 15)
                <button class="fa-regular fa-comment text-blue ml-2 b-show" title="Responder este comentário"></button>
            @endif
        </div>
    @endcan
</div>
<div
    class="w-full px-6 py-4 bg-gray-100 rounded-lg overflow-hidden shadow-md ring-1 ring-gray-400/10 mb-4 {{ $comment->comment_id ? 'm-l-' . $nivel : '' }}">
    <p>{!! $comment->comment !!}</p>
</div>
