<div x-data="{ uploadFileModal: false, tab: 'send_files', openCalculator: false }" x-cloak>
    <nav class="w-full bg-white">
        <div class="px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <a href="{{ route('content.home') }}" class="flex items-center text-xl font-bold w-64">
                        <img src="{{ asset('images/logo W horiz.png') }}">
                    </a>
                    {{-- <div class="hidden mr-6 lg:block"> --}}
                    {{-- <form action="#"> --}}
                    {{-- <div class="pl-2 border-bottom-300 "> --}}
                    {{-- <span for="search"><i class="fa-solid fa-search text-gray-semparar"></i></span> --}}
                    {{-- <x-input type="text" id="search" name="search" placeholder="Procurar..."></x-input> --}}
                    {{-- </div> --}}
                    {{-- </form> --}}
                    {{-- </div> --}}
                </div>
                <div class="flex items-center">
                    <!-- Icones do cabeçalho -->
                    <div class="flex w-full">
                        <x-dropdown align="right" width="100%">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div class="capitalize">
                                        {{ Str::words(Auth::user()->name, 1, '') }}
                                        {{ Str::words(Auth::user()->last_name, 1, '') }}
                                    </div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('auth.logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                        Sair
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <x-uploads.modal></x-uploads.modal>
    <x-calculator></x-calculator>
</div>
