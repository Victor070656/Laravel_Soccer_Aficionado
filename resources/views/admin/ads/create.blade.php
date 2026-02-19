<x-layouts::app :title="__('Create Ad')">
    <div class="max-w-3xl mx-auto space-y-6 p-2 sm:p-4">
        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ads.index') }}" class="w-10 h-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Create New Ad</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Upload an image and configure where it appears.</p>
            </div>
        </div>

        @if($errors->any())
        <div class="rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 p-4">
            <ul class="text-sm text-red-700 dark:text-red-400 list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-zinc-200/80 dark:border-zinc-700/80 bg-white dark:bg-zinc-800 shadow-sm overflow-hidden">
            @csrf
            <div class="p-6 space-y-6">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Ad Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-rose-500 focus:ring-rose-500/20 text-sm" placeholder="e.g. Nike Summer Campaign">
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Ad Image</label>
                    <div class="border-2 border-dashed border-zinc-200 dark:border-zinc-700 rounded-xl p-6 text-center hover:border-rose-400 transition relative" id="upload-area">
                        <input type="file" name="image" accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this)">
                        <div id="upload-placeholder">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Click to upload or drag and drop</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">JPG, PNG, GIF, WEBP up to 5MB</p>
                        </div>
                        <img id="image-preview" class="hidden mx-auto max-h-48 rounded-lg object-contain">
                    </div>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-2">
                        <strong>Recommended sizes:</strong> Sidebar: 300×250px · Feed: 728×90px · Banner: 970×250px · Welcome: 1200×300px
                    </p>
                </div>

                {{-- Link URL --}}
                <div>
                    <label for="link_url" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Destination URL <span class="text-zinc-400 font-normal">(optional)</span></label>
                    <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-rose-500 focus:ring-rose-500/20 text-sm" placeholder="https://example.com/landing-page">
                </div>

                {{-- Placement --}}
                <div>
                    <label for="placement" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Placement</label>
                    <select name="placement" id="placement" required class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-rose-500 focus:ring-rose-500/20 text-sm">
                        <option value="sidebar" {{ old('placement') === 'sidebar' ? 'selected' : '' }}>📱 Sidebar — Dashboard & page sidebars</option>
                        <option value="feed" {{ old('placement') === 'feed' ? 'selected' : '' }}>📰 Feed — Between posts in the feed</option>
                        <option value="banner" {{ old('placement') === 'banner' ? 'selected' : '' }}>🖼️ Banner — Top of main content pages</option>
                        <option value="welcome" {{ old('placement') === 'welcome' ? 'selected' : '' }}>🏠 Welcome — Landing page</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Start Date --}}
                    <div>
                        <label for="starts_at" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Start Date <span class="text-zinc-400 font-normal">(optional)</span></label>
                        <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-rose-500 focus:ring-rose-500/20 text-sm">
                        <p class="text-xs text-zinc-400 mt-1">Leave empty to start immediately.</p>
                    </div>
                    {{-- End Date --}}
                    <div>
                        <label for="ends_at" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">End Date <span class="text-zinc-400 font-normal">(optional)</span></label>
                        <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at') }}" class="w-full rounded-xl border-zinc-200 dark:border-zinc-600 dark:bg-zinc-900/50 dark:text-white focus:border-rose-500 focus:ring-rose-500/20 text-sm">
                        <p class="text-xs text-zinc-400 mt-1">Leave empty to run indefinitely.</p>
                    </div>
                </div>

                {{-- Active toggle --}}
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="rounded border-zinc-300 dark:border-zinc-600 text-rose-600 focus:ring-rose-500/20">
                    <label for="is_active" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Active — start showing this ad immediately</label>
                </div>
            </div>

            {{-- Submit --}}
            <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-900/30 border-t border-zinc-100 dark:border-zinc-700/60 flex justify-end gap-3">
                <a href="{{ route('admin.ads.index') }}" class="rounded-xl px-5 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">Cancel</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-rose-600 to-pink-500 px-6 py-2.5 text-sm font-semibold text-white hover:from-rose-500 hover:to-pink-400 transition-all shadow-md shadow-rose-600/20 hover:scale-105">
                    Create Ad
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-layouts::app>
