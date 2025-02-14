<?php

namespace Wallo\FilamentCompanies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Wallo\FilamentCompanies\Contracts\AddsCompanyEmployees;
use Wallo\FilamentCompanies\Contracts\CreatesCompanies;
use Wallo\FilamentCompanies\Contracts\DeletesCompanies;
use Wallo\FilamentCompanies\Contracts\DeletesUsers;
use Wallo\FilamentCompanies\Contracts\InvitesCompanyEmployees;
use Wallo\FilamentCompanies\Contracts\RemovesCompanyEmployees;
use Wallo\FilamentCompanies\Contracts\UpdatesCompanyNames;

class FilamentCompanies
{
    /**
     * Indicates if Company routes will be registered.
     *
     * @var bool
     */
    public static $registersRoutes = true;

    /**
     * The roles that are available to assign to users.
     *
     * @var array
     */
    public static $roles = [];

    /**
     * The permissions that exist within the application.
     *
     * @var array
     */
    public static $permissions = [];

    /**
     * The default permissions that should be available to new entities.
     *
     * @var array
     */
    public static $defaultPermissions = [];

    /**
     * The user model that should be used by Company.
     *
     * @var string
     */
    public static $userModel = 'App\\Models\\User';

    /**
     * The company model that should be used by Company.
     *
     * @var string
     */
    public static $companyModel = 'App\\Models\\Company';

    /**
     * The employeeship model that should be used by Company.
     *
     * @var string
     */
    public static $employeeshipModel = 'App\\Models\\Employeeship';

    /**
     * The company invitation model that should be used by Company.
     *
     * @var string
     */
    public static $companyInvitationModel = 'App\\Models\\CompanyInvitation';

    /**
     * Determine if Company has registered roles.
     *
     * @return bool
     */
    public static function hasRoles()
    {
        return count(static::$roles) > 0;
    }

    /**
     * Find the role with the given key.
     *
     * @param  string  $key
     * @return \Wallo\FilamentCompanies\Role
     */
    public static function findRole(string $key)
    {
        return static::$roles[$key] ?? null;
    }

    /**
     * Define a role.
     *
     * @param  string  $key
     * @param  string  $name
     * @param  array  $permissions
     * @return \Wallo\FilamentCompanies\Role
     */
    public static function role(string $key, string $name, array $permissions)
    {
        static::$permissions = collect(array_merge(static::$permissions, $permissions))
                                    ->unique()
                                    ->sort()
                                    ->values()
                                    ->all();

        return tap(new Role($key, $name, $permissions), function ($role) use ($key) {
            static::$roles[$key] = $role;
        });
    }

    /**
     * Determine if any permissions have been registered with Company.
     *
     * @return bool
     */
    public static function hasPermissions()
    {
        return count(static::$permissions) > 0;
    }

    /**
     * Define the available API token permissions.
     *
     * @param  array  $permissions
     * @return static
     */
    public static function permissions(array $permissions)
    {
        static::$permissions = $permissions;

        return new static;
    }

    /**
     * Define the default permissions that should be available to new API tokens.
     *
     * @param  array  $permissions
     * @return static
     */
    public static function defaultApiTokenPermissions(array $permissions)
    {
        static::$defaultPermissions = $permissions;

        return new static;
    }

    /**
     * Return the permissions in the given list that are actually defined permissions for the application.
     *
     * @param  array  $permissions
     * @return array
     */
    public static function validPermissions(array $permissions)
    {
        return array_values(array_intersect($permissions, static::$permissions));
    }

    /**
     * Determine if Company is managing profile photos.
     *
     * @return bool
     */
    public static function managesProfilePhotos()
    {
        return Features::managesProfilePhotos();
    }

    /**
     * Determine if Company is supporting API features.
     *
     * @return bool
     */
    public static function hasApiFeatures()
    {
        return Features::hasApiFeatures();
    }

    /**
     * Determine if Company is supporting company features.
     *
     * @return bool
     */
    public static function hasCompanyFeatures()
    {
        return Features::hasCompanyFeatures();
    }

    /**
     * Determine if a given user model utilizes the "HasCompanies" trait.
     *
     * @param  \Illuminate\Database\Eloquent\Model
     * @return bool
     */
    public static function userHasCompanyFeatures($user)
    {
        return (array_key_exists(HasCompanies::class, class_uses_recursive($user)) ||
                method_exists($user, 'currentCompany')) &&
                static::hasCompanyFeatures();
    }

    /**
     * Determine if the application is using the terms confirmation feature.
     *
     * @return bool
     */
    public static function hasTermsAndPrivacyPolicyFeature()
    {
        return Features::hasTermsAndPrivacyPolicyFeature();
    }

    /**
     * Determine if the application is using any account deletion features.
     *
     * @return bool
     */
    public static function hasAccountDeletionFeatures()
    {
        return Features::hasAccountDeletionFeatures();
    }

    /**
     * Find a user instance by the given ID.
     *
     * @param  int  $id
     * @return mixed
     */
    public static function findUserByIdOrFail($id)
    {
        return static::newUserModel()->where('id', $id)->firstOrFail();
    }

    /**
     * Find a user instance by the given email address or fail.
     *
     * @param  string  $email
     * @return mixed
     */
    public static function findUserByEmailOrFail(string $email)
    {
        return static::newUserModel()->where('email', $email)->firstOrFail();
    }

    /**
     * Get the name of the user model used by the application.
     *
     * @return string
     */
    public static function userModel()
    {
        return static::$userModel;
    }

    /**
     * Get a new instance of the user model.
     *
     * @return mixed
     */
    public static function newUserModel()
    {
        $model = static::userModel();

        return new $model;
    }

    /**
     * Specify the user model that should be used by Company.
     *
     * @param  string  $model
     * @return static
     */
    public static function useUserModel(string $model)
    {
        static::$userModel = $model;

        return new static;
    }

    /**
     * Get the name of the company model used by the application.
     *
     * @return string
     */
    public static function companyModel()
    {
        return static::$companyModel;
    }

    /**
     * Get a new instance of the company model.
     *
     * @return mixed
     */
    public static function newCompanyModel()
    {
        $model = static::companyModel();

        return new $model;
    }

    /**
     * Specify the company model that should be used by Company.
     *
     * @param  string  $model
     * @return static
     */
    public static function useCompanyModel(string $model)
    {
        static::$companyModel = $model;

        return new static;
    }

    /**
     * Get the name of the employeeship model used by the application.
     *
     * @return string
     */
    public static function employeeshipModel()
    {
        return static::$employeeshipModel;
    }

    /**
     * Specify the employeeship model that should be used by Company.
     *
     * @param  string  $model
     * @return static
     */
    public static function useEmployeeshipModel(string $model)
    {
        static::$employeeshipModel = $model;

        return new static;
    }

    /**
     * Get the name of the company invitation model used by the application.
     *
     * @return string
     */
    public static function companyInvitationModel()
    {
        return static::$companyInvitationModel;
    }

    /**
     * Specify the company invitation model that should be used by Company.
     *
     * @param  string  $model
     * @return static
     */
    public static function useCompanyInvitationModel(string $model)
    {
        static::$companyInvitationModel = $model;

        return new static;
    }

    /**
     * Register a class / callback that should be used to create companies.
     *
     * @param  string  $class
     * @return void
     */
    public static function createCompaniesUsing(string $class)
    {
        return app()->singleton(CreatesCompanies::class, $class);
    }

    /**
     * Register a class / callback that should be used to update company names.
     *
     * @param  string  $class
     * @return void
     */
    public static function updateCompanyNamesUsing(string $class)
    {
        return app()->singleton(UpdatesCompanyNames::class, $class);
    }

    /**
     * Register a class / callback that should be used to add company employees.
     *
     * @param  string  $class
     * @return void
     */
    public static function addCompanyEmployeesUsing(string $class)
    {
        return app()->singleton(AddsCompanyEmployees::class, $class);
    }

    /**
     * Register a class / callback that should be used to add company employees.
     *
     * @param  string  $class
     * @return void
     */
    public static function inviteCompanyEmployeesUsing(string $class)
    {
        return app()->singleton(InvitesCompanyEmployees::class, $class);
    }

    /**
     * Register a class / callback that should be used to remove company employees.
     *
     * @param  string  $class
     * @return void
     */
    public static function removeCompanyEmployeesUsing(string $class)
    {
        return app()->singleton(RemovesCompanyEmployees::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete companies.
     *
     * @param  string  $class
     * @return void
     */
    public static function deleteCompaniesUsing(string $class)
    {
        return app()->singleton(DeletesCompanies::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete users.
     *
     * @param  string  $class
     * @return void
     */
    public static function deleteUsersUsing(string $class)
    {
        return app()->singleton(DeletesUsers::class, $class);
    }

    /**
     * Find the path to a localized Markdown resource.
     *
     * @param  string  $name
     * @return string|null
     */
    public static function localizedMarkdownPath($name)
    {
        $localName = preg_replace('#(\.md)$#i', '.'.app()->getLocale().'$1', $name);

        return Arr::first([
            resource_path('markdown/'.$localName),
            resource_path('markdown/'.$name),
        ], function ($path) {
            return file_exists($path);
        });
    }

    /**
     * Configure Company to not register its routes.
     *
     * @return static
     */
    public static function ignoreRoutes()
    {
        static::$registersRoutes = false;

        return new static;
    }
}
