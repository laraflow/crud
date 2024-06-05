<?php

namespace Laraflow\ApiCrud\Support;

class Stub
{
    /**
     * The base path of stub file.
     *
     * @var null|string
     */
    protected static $basePath = null;

    /**
     * The stub path.
     *
     * @var string
     */
    protected $path;

    /**
     * The replacements array.
     *
     * @var array
     */
    protected $replaces = [];

    /**
     * The constructor.
     *
     * @param  string  $path
     */
    public function __construct($path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;

        self::setBasePath(base_path('stubs/api-crud'));

    }

    /**
     * Create new self instance.
     *
     * @param  string  $path
     * @return self
     */
    public static function create($path, array $replaces = [])
    {
        return new static($path, $replaces);
    }

    /**
     * Save stub to specific path.
     *
     * @param  string  $path
     * @param  string  $filename
     * @return bool
     */
    public function saveTo($path, $filename)
    {
        return file_put_contents($path.'/'.$filename, $this->getContents());
    }

    /**
     * Get stub contents.
     *
     * @return mixed|string
     */
    public function getContents()
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace('$'.strtoupper($search).'$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getPath()
    {
        $path = static::getBasePath().$this->path;

        return file_exists($path) ? $path : __DIR__.'/../../../stubs'.$this->path;
    }

    /**
     * Set stub path.
     *
     * @param  string  $path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get base path.
     *
     * @return string|null
     */
    public static function getBasePath()
    {
        return static::$basePath;
    }

    /**
     * Set base path.
     *
     * @param  string  $path
     */
    public static function setBasePath($path)
    {
        static::$basePath = $path;
    }

    /**
     * Set replacements array.
     *
     *
     * @return $this
     */
    public function replace(array $replaces = [])
    {
        $this->replaces = $replaces;

        return $this;
    }

    /**
     * Get replacements.
     *
     * @return array
     */
    public function getReplaces()
    {
        return $this->replaces;
    }

    /**
     * Handle magic method __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get stub contents.
     *
     * @return string
     */
    public function render()
    {
        return $this->getContents();
    }
}
