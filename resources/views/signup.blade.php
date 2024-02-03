@extends('layout')

@section('content')
<section class="bg-gray-50">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="/" class="flex items-center mb-6 text-2xl font-semibold text-gray-900">
        <svg fill="#000000" width="800px" height="800px" viewBox="-1 0 19 19" xmlns="http://www.w3.org/2000/svg" class="cf-icon-svg h-8 w-auto" alt="Blog">
            <path d="M16.417 9.579A7.917 7.917 0 1 1 8.5 1.662a7.917 7.917 0 0 1 7.917 7.917zM4.63 8.182l2.45-2.449-.966-.966a.794.794 0 0 0-1.12 0l-1.329 1.33a.794.794 0 0 0 0 1.12zm8.23 5.961c.13 0 .206-.1.173-.247l-.573-2.542a1.289 1.289 0 0 0-.292-.532l-4.53-4.53-.634.636 4.529 4.529.252-.252a.793.793 0 0 1 .147.268l.253 1.124-.69.69-1.125-.252a.799.799 0 0 1-.268-.148l.871-.87-4.53-4.53L5.19 8.742l4.53 4.528a1.294 1.294 0 0 0 .533.293l2.542.573a.3.3 0 0 0 .066.008z"/>
        </svg>
          <span class="px-3 text-3xl">PostIt</span>    
      </a>
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 ">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl ">
                  Create and account
              </h1>
              <form class="space-y-4 md:space-y-6" action="#">
                  <div>
                      <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your email</label>
                      <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " placeholder="name@company.com" required="">
                  </div>
                  <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Password</label>
                      <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required="">
                  </div>
                  <div>
                      <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 ">Confirm password</label>
                      <input type="password" name="confirm-password" id="confirm-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 " required="">
                  </div>
                  
                  <button type="submit" class="w-full text-white bg-black  focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Create an account</button>
                  <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                      Already have an account? <a href="/signin" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login here</a>
                  </p>
              </form>
          </div>
      </div>
  </div>
</section>
@stop