<?php

namespace Chr15k\Repository;

use Closure, Exception;
use Illuminate\Database\Eloquent\Model;
use Chr15k\Repository\Contracts\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    /**
     * Eloquent model to back the repository with.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Current repository errors.
     *
     * @var array
     */
    protected $errorsStore;

    /**
     * Constructor.
     *
     * Accepts Eloquent model to use as backer. Can and should be overridden in
     * implementing class to typehint correct model.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->errorsStore = [];
    }

    /**
     * Fetch all entities.
     *
     * @param  mixed                          $related Optional set of relationships to load with entities
     * @return \Illuminate\Support\Collection          Set of entities
     */
    public function all($related = [])
    {
        $this->errorsStore = [];

        return $this->model->with($related)->get();
    }

    /**
     * Get a paginated set of entities from the repository.
     *
     * @param  int                              $perPage Number of entities to retrieve
     * @param  mixed                            $related Optional set of relationships to load with entities
     * @return \Illuminate\Pagination\Paginator          Set of entities
     */
    public function paginate($perPage = self::PAGINATE_PER_PAGE, $related = [])
    {
        $this->errorsStore = [];

        return $this->model->with($related)->paginate($perPage);
    }

    /**
     * Get a lazy collection for the given query.
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function cursor()
    {
        return $this->model->cursor();
    }

    /**
     * Create new entity, persisting it to the database at the same time.
     *
     * @param  array         $attributes Attributes to set on entity
     * @param  mixed         $related    Optional set of relationships to load on created entity
     * @return mixed|boolean             Created entity, or false if could not be created
     */
    public function create(array $attributes = [], $related = [])
    {
        $this->errorsStore = [];

        try {
            $instance = $this->model->create($attributes);

            if ($instance->getKey()) {
                return $instance->load($related);
            }
        } catch (Exception $e) {
            $this->errorsStore = $e->getMessage();
        }

        return false;
    }

    /**
     * Get a new entity, but don't persist it.
     *
     * @param  array  $attributes Attributes to set on entity
     * @return void
     */
    public function getNew(array $attributes = [])
    {
        $class = get_class($this->model);

        return new $class($attributes);
    }

    /**
     * Retrieve an entity.
     *
     * @param  mixed         $id      Primary key of entity to retrieve
     * @param  mixed         $related Optional set of relationships to load with entity
     * @return mixed|boolean          Found entity, or false if not found
     */
    public function find($id, $related = [])
    {
        $this->errorsStore = [];

        return $this->model->with($related)->find($id);
    }

    /**
     * Retrieve an entity.
     *
     * @param  mixed         $id      Primary key of entity to retrieve
     * @param  mixed         $related Optional set of relationships to load with entity
     * @return mixed|boolean          Found entity, or false if not found
     */
    public function findOrFail($id, $related = [])
    {
        $this->errorsStore = [];

        return $this->model->with($related)->findOrFail($id);
    }

    /**
     * Update an entity with by its ID, with the given set of attributes.
     *
     * @param  mixed         $id         Primary key of entity to update
     * @param  array         $attributes Attributes to update on entity
     * @param  mixed         $related    Optional set of relationships to load with entity
     * @return mixed|boolean             Entity if updated successfully, false if not
     */
    public function update($id, array $attributes = [], $related = [])
    {
        $this->errorsStore = [];

        $instance = $this->model->find($id);

        try {
            if ($instance) {
                $saved = $instance->update($attributes) ? $instance : false;

                return $saved ? $saved->load($related) : false;
            }
        } catch (Exception $e) {
            $this->errorsStore = $e->getMessage();
        }

        return false;
    }

    /**
     * Retrieve an entity if it can be retrieved by key,
     * but if not, create a new one.
     *
     * @param  mixed $id      Primary key
     * @param  mixed $related Optional set of relationships to load with entity
     * @return mixed          Found or new entity
     */
    public function findOrNew($id = null, $related = [])
    {
        $this->errorsStore = [];

        return $this->model->with($related)->firstOrNew([
            $this->model->getKeyName() => $id
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function chunk($size, Closure $cb)
    {
        return $this->model->chunk($size, $cb);
    }

    /**
     * Remove an entity from storage.
     *
     * @param  mixed   $id Primary key or keys of entity(ies) to destroy
     * @return boolean     True if destroyed successfully, false if not
     */
    public function destroy($id)
    {
        $this->errorsStore = [];

        return $this->model->destroy($id);
    }

    /**
     * Fetch any errors for the last operation that was performed on
     * repository.
     *
     * @return mixed Errors
     */
    public function errors()
    {
        return $this->errorsStore;
    }

    /**
     * Get the model instance.
     *
     * @return Model
     */
    public function model()
    {
        return $this->model;
    }
}
