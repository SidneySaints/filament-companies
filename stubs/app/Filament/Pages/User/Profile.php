<?php

namespace App\Filament\Pages\User;

use Filament\Pages\Page;

class Profile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.user.profile';

    protected static bool $shouldRegisterNavigation = false;
}
