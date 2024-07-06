<?php

namespace Laraflow\Crud\Traits;

trait ModelExceptionTrait
{
    /**
     * Name of the affected Eloquent model.
     *
     * @var class-string
     */
    protected $model;

    /**
     * The affected model IDs.
     *
     * @var int|string
     */
    protected $id;

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the affected Eloquent model and instance ids.
     *
     * @param  class-string  $model
     * @param  null  $id
     * @return $this
     */
    public function setModel($model, $id = null)
    {
        $this->model = class_basename($model);

        $this->id = $id;

        $this->setMessage();

        return $this;
    }

    /**
     * @return void
     */
    private function setMessage()
    {
        $this->message = match ($this->type) {
            'delete' => __('crud::messages.exception.delete', ['model' => $this->getModel(), 'id' => $this->getId()]),
            'store' => __('crud::messages.exception.store', ['model' => $this->getModel()]),
            'update' => __('crud::messages.exception.update', ['model' => $this->getModel(), 'id' => $this->getId()]),
            default => __('crud::messages.exception.default')
        };
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }
}
