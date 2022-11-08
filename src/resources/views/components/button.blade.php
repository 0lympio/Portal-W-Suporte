<button {{ $attributes->merge(['type' => 'submit', 'class' => 'flex justify-center px-4 py-2 rounded cursor-pointer duration-200 hover:scale-110 focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
