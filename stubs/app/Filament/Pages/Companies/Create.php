<?php

namespace App\Filament\Pages\Companies;

use Filament\Pages\Page;

class Create extends Page
{
    protected static ?string $title = 'Create Company';

    protected static ?string $slug = 'create';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.companies.create';

    protected static bool $shouldRegisterNavigation = false;
}
