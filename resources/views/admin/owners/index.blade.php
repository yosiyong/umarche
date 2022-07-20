<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    エロクアント
                    <table class="table-auto w-full text-left whitespace-no-wrap">
                        @foreach ($e_all as $e_owner)
                        <tr>
                            <td>{{ $e_owner->name }}</td>
                            <td>{{ $e_owner->created_at }}</td>
                            <td>{{ $e_owner->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </table>

                    <br>
                    クエリビルダ
                    <table class="table-auto w-full text-left whitespace-no-wrap">
                        @foreach ($q_get as $q_owner)
                        <tr>
                            <td> {{ $q_owner->name }}</td>
                            <td>{{ $q_owner->created_at }}</td>
                            <td>{{ Carbon\Carbon::parse($q_owner->created_at)->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
