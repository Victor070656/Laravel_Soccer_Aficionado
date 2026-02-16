<x-layouts::app :title="__('Add Club')">
    <div class="max-w-2xl mx-auto space-y-6">
        <a href="{{ route('admin.clubs.index') }}" class="text-sm text-green-600 hover:text-green-700">← Back to Clubs</a>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Add Club</h1>

        <form action="{{ route('admin.clubs.store') }}" method="POST" enctype="multipart/form-data" class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6 space-y-4">
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
                <button type="submit" class="rounded-lg bg-green-600 px-6 py-2 text-sm text-white hover:bg-green-700">Create Club</button>
            </div>
        </form>
    </div>
</x-layouts::app>
