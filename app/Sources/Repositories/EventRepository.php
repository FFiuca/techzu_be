<?php
namespace App\Sources\Repositories;

use App\Sources\Repositories\Bases\ModelRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;

abstract class EventRepository extends ModelRepository{
    abstract public function addBatch(array $data, $userId):bool;
    abstract public function readFromExcel(UploadedFile $data):array;

    abstract public function get(array $data): array|Collection;
}
