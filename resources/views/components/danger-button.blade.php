<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-600/20 transition ease-in-out duration-150 shadow-md shadow-red-600/20']) }}>
    {{ $slot }}
</button>
