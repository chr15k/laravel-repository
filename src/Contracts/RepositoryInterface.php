<?php

namespace Chr15k\Repository\Contracts;

interface RepositoryInterface
{
   /**
     * Fetch all entities from the service.
     *
     * @param  mixed                          $related Optional set of relationships to load with entities
     * @return \Illuminate\Support\Collection          Set of entities
     */
    public function all($related = []);

    /**
     * Get a paginated set of entities from the repository.
     *
     * @param  int                              $perPage Number of entities to retrieve
     * @param  mixed                            $related Optional set of relationships to load with entities
     * @return \Illuminate\Pagination\Paginator          Set of entities
     */
    public function paginate($perPage = 15, $related = []);

    /**
     * Get a lazy collection for the given query.
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function cursor();

    /**
     * Create new entity, persisting it to the database at the same time.
     *
     * @param  array         $attributes Attributes to set on entity
     * @param  mixed         $related    Optional set of relationships to load on created entity
     * @return mixed|boolean             Created entity, or false if could not be created
     */
    public function create(array $attributes = [], $related = []);

    /**
     * Get a new entity, but don't persist it.
     *
     * @param  array  $attributes Attributes to set on entity
     * @return void
     */
    public function getNew(array $attributes = []);

    /**
     * Retrieve an entity from the service.
     *
     * @param  mixed         $id      Primary key of entity to retrieve
     * @param  mixed         $related Optional set of relationships to load with entity
     * @return mixed|boolean          Found entity, or false if not found
     */
    public function find($id, $related = []);

    /**
     * Retrieve an entity.
     *
     * @param  mixed         $id      Primary key of entity to retrieve
     * @param  mixed         $related Optional set of relationships to load with entity
     * @return mixed|boolean          Found entity, or false if not found
     */
    public function findOrFail($id, $related = []);

    /**
     * Update an entity with by its ID, with the given set of attributes.
     *
     * @param  mixed         $id         Primary key of entity to update
     * @param  array         $attributes Attributes to update on entity
     * @param  mixed         $related    Optional set of relationships to load with entity
     * @return mixed|boolean             Entity if updated successfully, false if not
     */
    public function update($id, array $attributes = [], $related = []);

    /**
     * Retrieve an entity from the service if it can be retrieved by key,
     * but if not, create a new one.
     *
     * @param  mixed $id      Primary key
     * @param  mixed $related Optional set of relationships to load with entity
     * @return mixed          Found or new entity
     */
    public function findOrNew($id = null, $related = []);

    /**
     * Process entities in batches.
     *
     * @param  int      $size Batch size
     * @param  \Closure $cb   Callback. Passed batch of entities
     * @return void
     */
    public function chunk($size, Closure $cb);

    /**
     * Remove an entity from storage.
     *
     * @param  mixed   $id Primary key or keys of entity(ies) to destroy
     * @return boolean     True if destroyed successfully, false if not
     */
    public function destroy($id);

    /**
     * Fetch any errors for the last operation that was performed on
     * service.
     *
     * @return mixed Errors
     */
    public function errors();

    /**
     * Get the model instance.
     *
     * @return Model
     */
    public function model();
}
