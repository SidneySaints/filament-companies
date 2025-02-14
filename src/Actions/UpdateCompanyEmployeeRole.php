<?php

namespace Wallo\FilamentCompanies\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Wallo\FilamentCompanies\Events\CompanyEmployeeUpdated;
use Wallo\FilamentCompanies\FilamentCompanies;
use Wallo\FilamentCompanies\Rules\Role;

class UpdateCompanyEmployeeRole
{
    /**
     * Update the role for the given company employee.
     *
     * @param  mixed  $user
     * @param  mixed  $company
     * @param  int  $companyEmployeeId
     * @param  string  $role
     * @return void
     */
    public function update($user, $company, $companyEmployeeId, string $role)
    {
        Gate::forUser($user)->authorize('updateCompanyEmployee', $company);

        Validator::make([
            'role' => $role,
        ], [
            'role' => ['required', 'string', new Role],
        ])->validate();

        $company->users()->updateExistingPivot($companyEmployeeId, [
            'role' => $role,
        ]);

        CompanyEmployeeUpdated::dispatch($company->fresh(), FilamentCompanies::findUserByIdOrFail($companyEmployeeId));
    }
}
