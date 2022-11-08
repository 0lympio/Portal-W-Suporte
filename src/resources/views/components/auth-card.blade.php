<div class="h-screen w-screen relative flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[url('/public/images/imagem-tela-de-login.png')] bg-center bg-cover">
    <div
        class="absolute opacity-60 top-0 left-0 w-full h-full bg-black">
    </div>
    <div class="w-full z-10 sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class=" flex justify-center my-5">
            {{ $logo }}
        </div>
        {{ $slot }}
    </div>
</div>


