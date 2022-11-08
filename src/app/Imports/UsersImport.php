<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * @param array $row
     *
     * @return Model|User|null
     */
    public function model(array $row): Model|User|null
    {
        $role = Role::where('name', $row['funcao'])->first();
        $company = Company::where('name', $row['assessoria'])->first();

        $user = new User([
            'name' => $row['nome'],
            'last_name' => $row['sobrenome'],
            'username' => $row['username'],
            'email' => $row['email'],
            'password' => bcrypt($row['senha']),
            'company_id' => $company->id,
        ]);

        $user->assignRole([$role->id]);

        return $user;
    }

    // Se houver algum erro não insere as linhas seguintes e reverte as
    // linhas inseridas anteriormente. A mensagem de erro é mostrada na tela.
    public function rules(): array
    {
        return [
            '*.username' => [
                'required',
                Rule::unique('users', 'username')->whereNull('deleted_at')
            ],

            '*.email' => [
                'email',
                'nullable',
                Rule::unique('users', 'email')->whereNull('deleted_at')
            ],

            '*.nome' => ['required'],
            '*.sobrenome' => ['nullable'],
            '*.senha' => ['required'],
            '*.funcao' => ['required'],
            '*.assessoria' => ['nullable', 'exists:companies,name'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'username.unique' => 'Este nome de usuário já foi registrado',
            'email.unique' => 'Este email já foi registrado',
            'assessoria.exists' => 'Essa assessoria não existe/não foi cadastrada',
        ];
    }
}
