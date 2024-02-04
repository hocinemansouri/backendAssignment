@php
$user = Session::get('user');
$categories = Session::get('categories');
$userRole = $user->role??'';
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
</head>

<body class="h-screen justify-between">
  <header>
    <nav class="bg-white-800">
      <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
          <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
            <div class="flex flex-shrink-0 items-center">
              <a href="/posts">
                <svg fill="#000000" width="800px" height="800px" viewBox="-1 0 19 19" xmlns="http://www.w3.org/2000/svg" class="cf-icon-svg h-8 w-auto" alt="Blog">
                  <path d="M16.417 9.579A7.917 7.917 0 1 1 8.5 1.662a7.917 7.917 0 0 1 7.917 7.917zM4.63 8.182l2.45-2.449-.966-.966a.794.794 0 0 0-1.12 0l-1.329 1.33a.794.794 0 0 0 0 1.12zm8.23 5.961c.13 0 .206-.1.173-.247l-.573-2.542a1.289 1.289 0 0 0-.292-.532l-4.53-4.53-.634.636 4.529 4.529.252-.252a.793.793 0 0 1 .147.268l.253 1.124-.69.69-1.125-.252a.799.799 0 0 1-.268-.148l.871-.87-4.53-4.53L5.19 8.742l4.53 4.528a1.294 1.294 0 0 0 .533.293l2.542.573a.3.3 0 0 0 .066.008z" />
                </svg>
              </a>
            </div>
            <div class="hidden sm:ml-6 sm:block">
              <div class="flex space-x-4">
                <a href="/posts">
                  <h6 class="text-xl font-semibold">PostIT</h6>
                </a>
              </div>
            </div>
          </div>
          <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 space-x-4">
            @if (!$user)
            <a href="/signin" class="bg-gray-900 text-white rounded-md px-4 py-2 text-sm font-medium">Signin</a>
            <a href="/signup" class="bg-gray-900 text-white rounded-md px-4 py-2 text-sm font-medium">Signup</a>
            @elseif(in_array($userRole,['moderator','admin']))
            <button id="modalBtn" class="bg-gray-900 text-white rounded-md px-4 py-2 text-sm font-medium" onclick="showModal()" type="button">New Post</button>
            @endif
            <div class="relative ml-3">
              <div>
                @if($user)
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdownMenu" type="button" class=" relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-expanded="false" aria-haspopup="true">
                  <span class="absolute -inset-1.5"></span>
                  <span class="sr-only">Open user menu</span>
                  <img class="h-8 w-8 rounded-full" src="{{ $user->profile_photo }}" alt="">
                </button>
                @endif
              </div>
              <div id="dropdownMenu" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                <!-- Active: "bg-gray-100", Not Active: "" -->
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
                <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-100 text-sm text-gray-700" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </nav>

    <div id="modalEl" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0 bg-white-800">
      <form class="bg-white p-6">
        <div class="space-y-6">
          <div class="">
            <h4 class="font-semibold">New Post</h4>
            <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
              <div class="sm:col-span-4">
                <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                <div class="mt-2">
                  <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                    <input type="text" name="title" id="title" class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6">
                  </div>
                </div>
              </div>

              <div class="col-span-full">
                <label for="sdesc" class="block text-sm font-medium leading-6 text-gray-900">Short description</label>
                <div class="mt-1">
                  <textarea id="sdesc" name="sdesc" rows="2" max="64" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                </div>
                <p class="mt-1 text-sm leading-6 text-gray-600">Write a short, attractive description to your post.</p>
              </div>

              <div class="col-span-full">
                <label for="content" class="block text-sm font-medium leading-6 text-gray-900">Content</label>
                <div class="mt-1">
                  <textarea id="content" name="content" rows="4" max="255" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                </div>
              </div>
              <div class="col-span-full">
                <label for="cover-photo" class="block text-sm font-medium leading-6 text-gray-900">Cover photo</label>
                <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6">
                  <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                    </svg>
                    <div class="mt-1 flex text-sm leading-6 text-gray-600">
                      <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                        <span>Upload a file</span>
                        <input id="file-upload" name="file-upload" type="file" class="sr-only">
                      </label>
                      <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Post's category</h2>
            
            <div class="mt-1">
              <fieldset >
                <div class="grid grid-cols-2 mt-3">
                  @if($categories)
                  @foreach($categories->unique('id') as $category)
                    <div class="flex items-center gap-x-3">
                      <input id="{{ $category['id'] }} " name="category" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600">
                      <label for="push-everything" class="block text-sm font-medium leading-6 text-gray-900">{{ $category['name'] }}</label>
                    </div>
                  @endforeach
                    @foreach ($categories as $category)
                    
                    @endforeach
                  @endif
                </div>
              </fieldset>
            </div>
          </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
          <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
          <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
        </div>
      </form>
    </div>
  </header>

  @yield('content')
  <footer class="bg-white b-0">
    <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
      <div class="md:flex md:justify-between">
        <div class="mb-6 md:mb-0">
          <a href="/posts" class="flex items-center">
            <svg fill="#000000" width="800px" height="800px" viewBox="-1 0 19 19" xmlns="http://www.w3.org/2000/svg" class="cf-icon-svg h-8 w-auto" alt="Blog">
              <path d="M16.417 9.579A7.917 7.917 0 1 1 8.5 1.662a7.917 7.917 0 0 1 7.917 7.917zM4.63 8.182l2.45-2.449-.966-.966a.794.794 0 0 0-1.12 0l-1.329 1.33a.794.794 0 0 0 0 1.12zm8.23 5.961c.13 0 .206-.1.173-.247l-.573-2.542a1.289 1.289 0 0 0-.292-.532l-4.53-4.53-.634.636 4.529 4.529.252-.252a.793.793 0 0 1 .147.268l.253 1.124-.69.69-1.125-.252a.799.799 0 0 1-.268-.148l.871-.87-4.53-4.53L5.19 8.742l4.53 4.528a1.294 1.294 0 0 0 .533.293l2.542.573a.3.3 0 0 0 .066.008z" />
            </svg>
            <span class="self-center text-2xl font-semibold whitespace-nowrap ml-4">PostIt</span>
          </a>
        </div>
        <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
          <div>
            <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Resources</h2>
            <ul class="text-gray-500 dark:text-gray-400 font-medium">
              <li class="mb-4">
                <a href="https://www.bohemia.net/" class="hover:underline">Bohemia Interactive</a>
              </li>
            </ul>
          </div>
          <div>
            <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Follow us</h2>
            <ul class="text-gray-500 dark:text-gray-400 font-medium">
              <li class="mb-4">
                <a href="https://github.com/hocinemansouri" class="hover:underline ">Github</a>
              </li>
              <li>
                <a href="https://discord.gg/" class="hover:underline">Discord</a>
              </li>
            </ul>
          </div>
          <div>
            <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Legal</h2>
            <ul class="text-gray-500 dark:text-gray-400 font-medium">
              <li class="mb-4">
                <a href="#" class="hover:underline">Privacy Policy</a>
              </li>
              <li>
                <a href="#" class="hover:underline">Terms &amp; Conditions</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
      <div class="sm:flex sm:items-center sm:justify-between">
        <span class="text-sm text-gray-500 sm:text-center ">© 2023 <a href="/" class="hover:underline">Bohemia Interactive-PostIt</a>. All Rights Reserved.
        </span>
      </div>
    </div>
  </footer>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
  <script>
    // set the modal menu element
    const $targetEl = document.getElementById('modalEl');
    const $btn = document.getElementById("modalBtn");
    // options with default values
    const options = {
      placement: 'top-center',
      backdrop: 'dynamic',
      backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
      closable: true,
      onHide: () => {
        console.log('modal is hidden');
      },
      onShow: () => {
        console.log('modal is shown');
      },
      onToggle: () => {
        console.log('modal has been toggled');
      },
    };

    // instance options object
    const instanceOptions = {
      id: 'modalEl',
      override: true
    };

    /*
     * $targetEl: required
     * options: optional
     */
    const modal = new Modal($targetEl, options, instanceOptions);

    function showModal() {
      modal.show();
    }
    if ($newPostBtn.clicked) {}
  </script>
</body>

</html>