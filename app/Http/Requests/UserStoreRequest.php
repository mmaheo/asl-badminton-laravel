<?php

namespace App\Http\Requests;

class UserStoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();

        if ($user->hasRole('admin'))
        {
            return true;
        }

        abort(401, 'Unauthorized action.');

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'    => 'required',
            'forname' => 'required',
            'email'   => 'unique:users,email|email',
            'role' => 'required|in:user,admin,ce',
            'active' => 'required|boolean',
        ];
    }
}
