@extends('layouts.auth')

@section('title', 'Восстановление пароля')

@section('content')
    <x-forms.form action="{{ route('auth.reset-password') }}" method="POST">
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

        <x-forms.text-input
            :isError="$errors->has('password')"
            type="password"
            name="password"
            placeholder="Пароль"
            required
        />
        @error('password')
        <x-forms.error :value="$message"/>
        @enderror

        <x-forms.text-input
            :isError="$errors->has('password_confirmation')"
            type="password"
            name="password_confirmation"
            placeholder="Повторите пароль"
            required
        />
        @error('password_confirmation')
        <x-forms.error :value="$message"/>
        @enderror

        <x-forms.primary-button :value="'Обновить пароль'"/>
        <x-slot:socialAuth></x-slot:socialAuth>
        <x-slot:buttons></x-slot:buttons>
    </x-forms.form>
@endsection
