<div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs space-y-3 relative group">
    <!-- Card Header / Actions -->
    <div class="flex justify-between items-start">
        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase 
            {{ $task->priority === 'high' ? 'bg-red-50 text-red-700 border border-red-100' : ($task->priority === 'medium' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-slate-50 text-slate-700 border border-slate-100') }}">
            {{ $task->priority === 'high' ? 'Haute' : ($task->priority === 'medium' ? 'Moyenne' : 'Basse') }}
        </span>
        <div class="flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
            <button wire:click="openModal({{ $task->id }})" class="text-slate-400 hover:text-indigo-600 transition-colors" title="Modifier">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </button>
            <button onclick="confirm('Supprimer cette tâche ?') || event.stopImmediatePropagation()" wire:click="deleteTask({{ $task->id }})" class="text-slate-400 hover:text-rose-600 transition-colors" title="Supprimer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>

    <!-- Title & Description -->
    <div>
        <h4 class="font-bold text-slate-800 text-sm leading-snug">{{ $task->title }}</h4>
        @if($task->description)
            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $task->description }}</p>
        @endif
    </div>

    <!-- Client Link -->
    <div class="border-t border-slate-100 pt-2 flex items-center justify-between text-xs">
        <span class="text-slate-400">Client:</span>
        <a href="{{ route('admin.clients.profile', $task->client_id) }}" class="text-indigo-650 hover:underline font-semibold flex items-center gap-0.5">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            {{ $task->client->first_name }} {{ $task->client->last_name }}
        </a>
    </div>

    <!-- Meta Details (Assigned & Due Date) -->
    <div class="flex justify-between items-center text-[10px] text-slate-400">
        <div>
            @if($task->assigned_user_id)
                <span>Attribuer à : <strong class="text-slate-600">{{ $task->assignee->name }}</strong></span>
            @else
                <span class="italic text-slate-400">Non assignée</span>
            @endif
        </div>
        @if($task->due_date)
            <div class="flex items-center gap-1 font-semibold font-mono {{ $task->due_date->isPast() && $task->status !== 'completed' ? 'text-red-650' : 'text-slate-500' }}">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $task->due_date->format('d/m/Y') }}
            </div>
        @endif
    </div>

    <!-- Column Transition Controls -->
    @if(isset($prevStatus) || isset($nextStatus))
        <div class="flex justify-between items-center border-t border-slate-100 pt-2 text-[10px]">
            @if(isset($prevStatus))
                <button wire:click="updateTaskStatus({{ $task->id }}, '{{ $prevStatus }}')" class="text-slate-400 hover:text-slate-700 font-semibold flex items-center gap-0.5">
                    ← {{ $prevLabel }}
                </button>
            @else
                <div></div>
            @endif

            @if(isset($nextStatus))
                <button wire:click="updateTaskStatus({{ $task->id }}, '{{ $nextStatus }}')" class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-0.5">
                    {{ $nextLabel }} →
                </button>
            @endif
        </div>
    @endif
</div>
