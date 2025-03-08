<?php
namespace App\Services\Custom\Essentials;

use Illuminate\Support\Facades\DB;

class VerifyInputsService
{
    /**
     * Verifica se um valor é único em uma coluna específica de uma tabela.
     *
     * @param  string $table
     * @param  string $column
     * @param  string $value
     * @param  string $locale
     * @return bool
     */
    public function checkUnique(
        string $table,
        string $column,
        string $value,
        string $locale
    ): bool {
        $response = $this->checkExistence($table, $column, $value, $locale);
        return $response ? true : false;
    }

    /**
     * Verifica se um valor é único em uma coluna específica de uma tabela, excluindo um ID específico.
     *
     * @param  string $table
     * @param  string $column
     * @param  string $value
     * @param  string $locale
     * @param  int $id
     * @param  string|null $idColumnName
     * @return bool
     */
    public function checkUniqueUpdate(
        string $table,
        string $column,
        string $value,
        string $locale,
        int $id,
        ?string $idColumnName = null
    ): bool {
        $response = $this->checkExistence($table, $column, $value, $locale);

        if (is_null($response)) {
            return false;
        }

        $idColumnName = $idColumnName ?? 'id';

        return $response->$idColumnName !== $id;
    }

    /**
     * Método auxiliar para verificar a existência de um registro.
     *
     * @param  string $table
     * @param  string $column
     * @param  string $value
     * @param  string $locale
     * @return object|null
     */
    private function checkExistence(
        string $table,
        string $column,
        string $value,
        string $locale
    ): ?object {
        return DB::table($table)
            ->where($column, $value)
            ->when($locale !== 'global', function ($query) use ($locale) {
                return $query->where('locale', $locale);
            })
            ->first();
    }
}
