@php
$user = Session::get('user');
$userRole = $user->role??'';
@endphp
@extends('layout')

@section('content')
<?php $imageUser = str_contains($data['user']['profile_photo'],'http') ? $data['user']['profile_photo'] : $data['user']['profile_image_url']; ?>
<main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white antialiased">
    <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
        <article class="mx-auto w-full max-w-2xl format format-sm sm:format-base lg:format-lg format-blue dark:format-invert">
            <header class="mb-4 lg:mb-6 not-format">
                <address class="flex justify-between items-center mb-6 not-italic">
                    <div class="inline-flex items-center mr-3 text-sm text-gray-900 ">
                        <img class="mr-4 w-16 h-16 rounded-full" src="{{ $imageUser }}" alt="Jese Leos">
                        <div>
                            <a href="#" rel="author" class="text-xl font-bold text-gray-900 ">{{ $data['user']['name']." ".$data['user']['surname']}}</a>
                            <p class="text-base text-gray-500 dark:text-gray-400"><time pubdate datetime="2022-02-08" title="February 8th, 2022">{{ $data['human_readable_created_at'] }}</time></p>
                        </div>
                    </div>
                    <div class="flex justify-between w-10 mx-4">
                        <a href="{{$data['id']}}/toggleLike" style= "{{ $user?? "pointer-events: none;" }}"> 
                            @if($data['liked_by_current_user'])
                            <svg xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            @endif
                            </a>
                        <p>{{ $data['postlikes_count'] }}</p>
                    </div>
                </address>
                <h1 class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 lg:mb-6 lg:text-4xl ">{{ $data['title']}}</h1>
            </header>
            <br />
            <?php $image = str_contains($data['image'],'http') ? $data['image'] : $data['image_url']; ?>
            <figure><img src="{{ $image }}" alt="">
                <figcaption>Digital art</figcaption>
            </figure>
            <br />
            <p class="lead">
                {{ $data['content']}}
            </p>
            <br />
            <section class="not-format">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg lg:text-2xl font-bold text-gray-900 ">Discussion ({{ $data['comments_count']}})</h2>
                </div>
                @if($user)
                <form class="mb-6"  action="{{$data['id']}}/comments/create" method="post" >
                @csrf
                    <div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 ">
                        <label for="comment" class="sr-only">Your comment</label>
                        <textarea name="content" id="comment" rows="6" class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 " placeholder="Write a comment..." required></textarea>
                    </div>
                    <button type="submit" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-black rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                        Post comment
                    </button>
                </form>
                @endif
                @foreach ($comments as $comment)
                <article class="p-6 mb-2 text-base bg-white rounded-lg ">
                    <footer class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                        <?php $commentUserImage = str_contains($comment['user']['profile_photo'],'http') ? $comment['user']['profile_photo'] : $comment['user']['profile_image_url']; ?>
                            <p class="inline-flex items-center mr-3 font-semibold text-sm text-gray-900 "><img class="mr-2 w-6 h-6 rounded-full" src="{{ $commentUserImage }}" alt="{{ $comment['user']['name'].' '.$comment['user']['surname'] }}">{{ $comment['user']['name']." ".$comment['user']['surname'] }} </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400"><time pubdate datetime="2022-02-08" title="February 8th, 2022">{{ $comment['human_readable_created_at'] }}</time></p>
                        </div>
                        <button id="dropdownComment1Button" data-dropdown-toggle="dropdownComment1" class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 " type="button">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                            </svg>
                            <span class="sr-only">Comment settings</span>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdownComment1" class="hidden z-10 w-36 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenuIconHorizontalButton">
                                <li>
                                    <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                </li>
                                <li>
                                    <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Remove</a>
                                </li>
                                <li>
                                    <a href="#" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Report</a>
                                </li>
                            </ul>
                        </div>
                    </footer>
                    <p>{{ $comment['content'] }} </p>

                </article>
                @endforeach

                {{ $comments->links() }}



            </section>
        </article>
    </div>
</main>

<script>
    $(function() {
        var $comments = $("#comments");
        var $ul = $("ul.pagination");
        $ul.hide(); // Prevent the default Laravel paginator from showing, but we need the links...

        $(".see-more").click(function() {
            $.get($ul.find("a[rel='next']").attr("href"), function(response) {
                $comments.append(
                    $(response).find("#comments").html()
                );
            });
        });
    });
</script>

@stop