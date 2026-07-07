<div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 space-y-6 mt-6">
    <div class="border-b border-slate-800 pb-3 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Test Case Management</h2>
            <p class="text-xs text-slate-500 mt-1">Visible test cases are shown as samples to contestants, while hidden ones are used strictly for evaluations.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-300">
            Total: {{ $problem->testCases->count() }}
        </span>
    </div>

    {{-- List of Existing Test Cases --}}
    <div class="space-y-4">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Current Test Cases</h3>
        
        @if($problem->testCases->isEmpty())
            <div class="rounded-xl border border-dashed border-slate-800 p-8 text-center">
                <svg class="mx-auto h-8 w-8 text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm text-slate-500">No test cases have been added to this problem yet.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($problem->testCases as $index => $tc)
                    <div class="rounded-xl border border-slate-800/80 bg-slate-950/20 p-4 space-y-3 relative transition-all duration-200 hover:border-slate-700/80">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Test Case #{{ $index + 1 }}</span>
                                @if($tc->is_hidden)
                                    <span class="inline-flex items-center rounded bg-amber-500/10 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-400 ring-1 ring-inset ring-amber-500/20">
                                        Hidden
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded bg-emerald-500/10 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-400 ring-1 ring-inset ring-emerald-500/20">
                                        Visible / Sample
                                    </span>
                                @endif
                            </div>
                            
                            {{-- Delete Action --}}
                            <form action="{{ route('test-cases.destroy', $tc) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this test case?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-semibold flex items-center gap-1 transition duration-150 focus:outline-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div class="space-y-1">
                                <span class="text-slate-500 uppercase tracking-wider font-semibold text-[10px]">Input</span>
                                <pre class="bg-slate-950/70 border border-slate-900/60 p-2.5 rounded-lg text-slate-300 font-mono overflow-x-auto max-h-32 select-all whitespace-pre-wrap">{{ $tc->input }}</pre>
                            </div>
                            <div class="space-y-1">
                                <span class="text-slate-500 uppercase tracking-wider font-semibold text-[10px]">Expected Output</span>
                                <pre class="bg-slate-950/70 border border-slate-900/60 p-2.5 rounded-lg text-slate-300 font-mono overflow-x-auto max-h-32 select-all whitespace-pre-wrap">{{ $tc->expected_output }}</pre>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Inline Add Form --}}
    <div class="border-t border-slate-800 pt-6">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-4">Add New Test Case</h3>
        
        <form action="{{ route('problems.test-cases.store', $problem) }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Input Area --}}
                <div class="space-y-1.5">
                    <label for="input" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Input <span class="text-rose-500">*</span></label>
                    <textarea id="input" name="input" rows="4" required
                              class="w-full px-4 py-2.5 rounded-xl text-xs text-slate-100 placeholder-slate-600 transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-800 bg-slate-900/50 font-mono"
                              placeholder="Test case inputs..."></textarea>
                    @error('input')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Expected Output Area --}}
                <div class="space-y-1.5">
                    <label for="expected_output" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Expected Output <span class="text-rose-500">*</span></label>
                    <textarea id="expected_output" name="expected_output" rows="4" required
                              class="w-full px-4 py-2.5 rounded-xl text-xs text-slate-100 placeholder-slate-600 transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-800 bg-slate-900/50 font-mono"
                              placeholder="Expected test case output..."></textarea>
                    @error('expected_output')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Hidden Toggle --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <input id="is_hidden" type="checkbox" name="is_hidden" value="1" {{ old('is_hidden') ? 'checked' : '' }}
                           class="w-4 h-4 rounded accent-indigo-500 bg-slate-900 border-slate-700 cursor-pointer">
                    <label for="is_hidden" class="text-sm font-medium text-slate-300 cursor-pointer select-none">
                        Hidden Test Case <span class="text-xs text-slate-500 font-normal">(Used for scoring only, hidden from contestants)</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit" 
                        class="flex items-center justify-center gap-2 py-2 px-5 rounded-xl text-xs font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-[.98]"
                        style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Test Case
                </button>
            </div>
        </form>
    </div>
</div>
