<x-filament-companies::grid-section class="mt-8">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <form wire:submit.prevent="updatePassword" class="col-span-2 mt-5 sm:col-span-1 md:mt-0">
        <x-filament::card>
            <div class="col-span-6 sm:col-span-4">
                <x-filament-companies::label for="current_password" value="{{ __('Current Password') }}" />
                <x-filament-companies::input id="current_password" type="password" class="mt-1 block w-full"
                    wire:model.defer="state.current_password" autocomplete="current-password" />
                <x-filament-companies::input-error for="current_password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-filament-companies::label for="password" value="{{ __('New Password') }}" />
                <x-filament-companies::input id="password" type="password" class="mt-1 block w-full"
                    wire:model.defer="state.password" autocomplete="new-password" />
                <x-filament-companies::input-error for="password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-filament-companies::label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-filament-companies::input id="password_confirmation" type="password" class="mt-1 block w-full"
                    wire:model.defer="state.password_confirmation" autocomplete="new-password" />
                <x-filament-companies::input-error for="password_confirmation" class="mt-2" />
            </div>

            <x-slot name="footer">
                <div class="text-left">
                    <x-filament::button type="submit">
                        {{ __('Save') }}
                    </x-filament::button>
                </div>
            </x-slot>
        </x-filament::card>
    </form>
</x-filament-companies::grid-section>
