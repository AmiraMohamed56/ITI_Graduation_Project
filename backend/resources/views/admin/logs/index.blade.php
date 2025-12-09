@extends('layouts.admin')
@section('title', 'Admin Logs')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="max-w-7xl mx-auto py-10 px-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Admin Logs</h1>
                <span class="bg-blue-600 text-white text-sm font-medium px-3 py-1 rounded">
                    {{ $logs->total() }}
                </span>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <span>⇅ Sort By :</span>
                <select onchange="window.location.href='?sort='+this.value" class="border-none bg-transparent cursor-pointer text-gray-600 dark:text-gray-300">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>
        </div>

        @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 rounded">
            {{ session('success') }}
        </div>
        @endif

        <!-- Filter Form -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <form method="GET" action="{{ route('admin.logs.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- User Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search User</label>
                    <input
                        type="text"
                        name="user"
                        placeholder="Search by user name..."
                        value="{{ $requestData['user'] ?? '' }}"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                </div>

                <!-- Action Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Action</label>
                    <select name="action" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                        <option value="{{ $action }}" {{ ($requestData['action'] ?? '') == $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Model Filter
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Model</label>
                    <select name="model" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">All Models</option>
                        @foreach($models as $model)
                        <option value="{{ $model }}" {{ ($requestData['model'] ?? '') == $model ? 'selected' : '' }}>
                            {{ $model }}
                        </option>
                        @endforeach
                    </select>
                </div> -->

                <!-- Filter Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-all duration-200">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
            <table class="w-full text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">ID</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">User</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Action</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Model ID</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">IP Address</th>
                        <th class="px-6 py-4 text-sm font-medium text-gray-700 dark:text-gray-200">Date</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">#{{ $log->id }}</td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($log->user)
                                @php
                                $image = $log->user->profile_pic ?? null;
                                $name = $log->user->name ?? 'User';
                                $initial = strtoupper(substr($name, 0, 1));
                                $imagePath = $image ? asset('storage/profile_pics/' . $image) : null;
                                @endphp

                                @if ($imagePath && file_exists(storage_path('app/public/profile_pics/' . $image)))
                                <img src="{{ $imagePath }}"
                                    class="w-8 h-8 rounded-full object-cover" alt="{{ $name }}">
                                @else
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold">
                                    {{ $initial }}
                                </div>
                                @endif

                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $name }}
                                </span>
                                @else
                                <div class="w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center text-white text-sm font-bold">
                                    S
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    System
                                </span>
                                @endif
                            </div>
                        </td>


                        <td class="px-6 py-4">
                            @php
                            $actionColors = [
                            'create' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                            'update' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                            'delete' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                            'login' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                            'logout' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                            ];
                            $color = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                            @endphp
                            <span class="text-xs font-medium px-3 py-1 rounded-full {{ $color }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>


                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            #{{ $log->model_id }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs">{{ $log->ip_address }}</code>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $log->created_at->format('Y-m-d') }}
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $log->created_at->format('H:i') }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No logs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>

@endsection
