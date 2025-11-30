@extends('layouts.app')

@section('content')
    <div class="m-8">
        <h1 class="text-4xl font-bold mb-4">Produk Populer</h1>
        <div class="grid grid-cols-5 gap-4 pb-8 min-w-full">
            @foreach (range(1, 5) as $i)
                <a class="w-full bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 border-2 border-indigo-300 flex flex-col"
                    href="{{ route('showcase.showcase') }}">
                    <div class="relative">
                        <img src="https://i.postimg.cc/Yqf821gm/monaco-gp-background.jpg" class="w-full h-40 object-cover">

                        <div class="absolute top-2 right-2 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded">
                            Lelang Live</div>
                    </div>
                    <div class="p-3 w-full">
                        <h3 class="text-md font-bold text-gray-900 truncate">McLaren MCL39</h3>
                        <p class="text-gray-500 text-xs mb-3 flex items-center gap-1">📍 Surabaya, Jatim</p>

                        <div class="flex justify-between text-[10px] w-full mb-3 border-y border-gray-100 py-2">
                            <div class="flex flex-col items-center font-bold">
                                <span>⚙️ Man.</span>
                            </div>
                            <div class="flex flex-col items-center font-bold">
                                <span>⛽ Turbo</span>
                            </div>
                            <div class="flex flex-col items-center font-bold">
                                <span>🛣️ 4500 Km</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <div class="text-left">
                                <span class="text-[10px] text-gray-400">Tawaran:</span>
                                <p class="text-sm font-bold text-indigo-600">Rp 485.000.000</p>
                            </div>

                            <button
                                class="w-full bg-indigo-600 text-white py-1.5 rounded-lg text-xs font-semibold hover:bg-indigo-700 transition">Tawar</button>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
