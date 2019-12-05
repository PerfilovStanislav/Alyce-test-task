<?php

namespace App\ModelManagers;

use App\Exceptions\AbilityExistsException;
use App\Exceptions\AbilityNotFoundException;
use App\Models\Ability;

class AbilityModelManager
{
    /**
     * Create new ability
     * @param array $attributes
     * @return int
     * @throws AbilityExistsException
     */
    public function create(array $attributes) : int
    {
        if (!is_null(Ability::query()->where('name', $attributes['name'])->first())) {
            throw new AbilityExistsException('Ability already exists');
        }

        $ability = new Ability($attributes);
        $ability->save();
        return $ability->id;
    }

    /**
     * Update ability
     * @param int $id
     * @param array $attributes
     * @return bool
     * @throws AbilityNotFoundException
     */
    public function update(int $id, array $attributes) : bool
    {
        return $this->getModelByIdOrFail($id)->fill($attributes)->save();
    }

    /**
     * Delete ability
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id) : bool
    {
        return $this->getModelByIdOrFail($id)->delete();
    }

    /**
     * Get model or fail
     * @param int $id
     * @return Ability
     * @throws AbilityNotFoundException
     */
    public function getModelByIdOrFail(int $id) : Ability
    {
        /** @var Ability $ability */
        if (!$ability = Ability::find($id)) {
            throw new AbilityNotFoundException('Ability doesn\'t exist');
        }
        return $ability;
    }
}