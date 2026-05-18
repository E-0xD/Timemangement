<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased">
        <div class="flex min-h-screen">

            {{-- Left branding panel (desktop only) --}}
            <div class="hidden lg:flex lg:w-5/12 bg-indigo-50 flex-col justify-between p-12">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <x-app-logo-icon class="size-7 text-indigo-600" />
                    <span class="text-slate-900 font-semibold text-base">{{ config('site.name') }}</span>
                </a>

                <div class="space-y-4">
                    <h1 class="text-3xl font-bold text-slate-900 leading-snug">
                        {{ config('site.tagline') }}
                    </h1>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-xs">
                        {{ config('site.description') }}
                    </p>
                    <ul class="space-y-2 pt-2">
                        @foreach (['Plan tasks, set goals, and stay on track.', 'Log study sessions and measure progress.', 'Collaborate and grow with your peers.'] as $point)
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <span class="mt-0.5 text-indigo-500 font-bold leading-none">&#x2713;</span>
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} {{ config('site.name') }}</p>
            </div>

            {{-- Right form panel --}}
            <div class="flex flex-1 flex-col justify-center px-8 py-12 lg:px-16">
                {{-- Mobile logo --}}
                <div class="lg:hidden mb-10 flex justify-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <x-app-logo-icon class="size-7 text-indigo-600" />
                        <span class="text-slate-900 font-semibold">{{ config('site.name') }}</span>
                    </a>
                </div>

                <div class="mx-auto w-full max-w-sm">
                    {{ $slot }}
                </div>
            </div>

        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
