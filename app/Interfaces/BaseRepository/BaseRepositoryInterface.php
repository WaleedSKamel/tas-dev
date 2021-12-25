<?php


namespace App\Interfaces\BaseRepository;


interface BaseRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function show($id);

    public function edit($id);

    public function update($id,array $data);

    public function delete($id);

    public function deleteWhere($conditions);

    public function destroy($collect);

    public function restore($id);

    public function getModel();

    public function setModel($model);

    public function with($relation);

    public function whereHas($relation,$callback);
}
