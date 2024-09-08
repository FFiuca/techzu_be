<?php
namespace App\Sources\Repositories\Bases;

use Illuminate\Database\Eloquent\Model;

abstract class ModelRepository {

    abstract public function add(array $data): Model|bool|array;
    abstract public function update(int|string $id,array $data): Model|bool|array;
    abstract public function delete(int|string $id): bool;
    abstract public function detail(int|string $id): Model|array;
}

?>
