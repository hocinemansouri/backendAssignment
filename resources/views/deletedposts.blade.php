@extends('layout')

@section('content')
<main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white antialiased">

<div class="relative overflow-x-auto sm:rounded-lg">
<div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                Title
                </th>
                <th scope="col" class="px-6 py-3">
                User
                </th>
                <th scope="col" class="px-6 py-3">
                Category
                </th>
                <th scope="col" class="px-6 py-3">
                Deleted at
                </th>
                <th scope="col" class="px-6 py-3">
                    <span class="sr-only">Restore</span>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $post)
                
           <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $post['title'] }}
                </th>
                <td class="px-6 py-4">
                    {{ $post['user']['name']." ".$post['user']['surname'] }}
                </td>
                <td class="px-6 py-4">
                {{ $post['category']['name'] }}
                </td>
                <td class="px-6 py-4">
                {{ $post['deleted_at'] }}
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="post/{{ $post['id'] }}/restorePost" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Restore post</a>
                </td>
            </tr>
            @endforeach
            
        </tbody>
    </table>
</div>
</div>
</main>
@stop