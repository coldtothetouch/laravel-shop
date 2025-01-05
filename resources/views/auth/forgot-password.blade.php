@extends('layouts.auth')

@section('title', 'Восстановление пароля')

@section('content')
    <x-forms.form action="{{ route('auth.send-email') }}" method="POST">
        @csrf
        <x-slot:title>Восстановление пароля</x-slot:title>

        <x-forms.text-input
            :isError="$errors->has('email')"
            type="email"
            name="email"
            placeholder="E-mail"
            required
        />
        @error('email')
        <x-forms.error :value="$message"/>
        @enderror

        <x-forms.primary-button :value="'Отправить'"/>

        <x-slot:socialAuth></x-slot:socialAuth>

        <x-slot:buttons>
            <div class="mt-5 text-xxs md:text-xs"><a href="{{ route('auth.login.index') }}"
                                                    class="text-white hover:text-white/70 font-bold">Вспомнил пароль</a>
            </div>
        </x-slot:buttons>
    </x-forms.form>
@endsection
