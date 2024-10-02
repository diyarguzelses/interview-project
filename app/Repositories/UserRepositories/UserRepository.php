<?php

namespace App\Repositories\UserRepositories;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function fetchAllUsers()
    {
        return User::with('company')->get();
    }

    public function getUserById(int $id)
    {
        return User::with('company')->find($id);
    }

    public function createUser(array $data)
    {
        $company = Company::firstOrCreate(['name' => $data['company_name']]);

        $user = User::create(array_merge($data, ['company_id' => $company->id]));

        return [
            'user' => $user,
            'company' => $company
        ];
    }

    public function updateUser(int $id, array $data)
    {
        $user = User::find($id);

        if ($user) {
            if (isset($data['company_name'])) {
                $company = Company::firstOrCreate(['name' => $data['company_name']]);
                $data['company_id'] = $company->id;
            }
            $user->update($data);

            $user->company;

            return $user;
        } else {
            return null;
        }

    }

    public function deleteUser(int $id): bool
    {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }

        return false;
    }

    public function searchUsers(array $filters)
    {
        $search = User::query();

        foreach ($filters as $key => $value) {
            if ($key === 'company_name' && $value) {
                $search->whereHas('company', function ($query) use ($value) {
                    $query->where('name', 'like', "%{$value}%");
                });
            } elseif ($value) {
                $search->where($key, 'like', "%{$value}%");
            }
        }
        return $search->with('company')->get();
    }
}
