<x-layouts::app :title="__('Add Club')">
    <div class="max-w-2xl mx-auto space-y-6">
        <a href="{{ route('admin.clubs.index') }}" class="inline-flex items-center gap-1 text-sm text-green-500 hover:text-green-400 font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Clubs
        </a>
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 p-6">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <h1 class="text-2xl font-bold text-white relative">⚽ Add New Club</h1>
        </div>

        <form action="{{ route('admin.clubs.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-zinc-200 dark:border-zinc-700/50 bg-white dark:bg-zinc-800/50 backdrop-blur-sm p-6 space-y-4 shadow-xl shadow-black/5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name *</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Short Name</label>
                <input type="text" name="short_name" value="{{ old('short_name') }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white" maxlength="5">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Country *</label>
                <input type="text" name="country" required value="{{ old('country') }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">City</label>
                <input type="text" name="city" value="{{ old('city') }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Stadium</label>
                <input type="text" name="stadium" value="{{ old('stadium') }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Founded Year</label>
                <input type="number" name="founded_year" value="{{ old('founded_year') }}" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white" min="1800" max="{{ date('Y') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Logo</label>
                <input type="file" name="logo" accept="image/*" class="text-sm text-zinc-500">
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" class="rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-2.5 text-sm font-semibold text-white hover:from-green-500 hover:to-emerald-500 shadow-lg shadow-green-500/25 transition-all duration-300">Create Club</button>
            </div>
        </form>
    </div>
</x-layouts::app>
