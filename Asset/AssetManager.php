<?php

namespace Solution\CodeMirrorBundle\Asset;

use Doctrine\Common\Cache\PhpFileCache;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Config\FileLocator;

class AssetManager
{
    const CACHE_MODES_NAME = 'solution.code.mirror.modes';
    const CACHE_THEMES_NAME = 'solution.code.mirror.themes';
    const CACHE_ADDONS_NAME = 'solution.code.mirror.addons';

    /** @var  FileLocator */
    protected $fileLocator;

    protected $modes = array();

    protected $addons = array();

    protected $themes = array();

    protected $modeDirs = array();

    protected $themesDirs = array();

    protected $addonDirs = array();

    protected $cacheDriver;

    protected $env;

    function __construct($fileLocator, $modeDirs, $themesDirs, $addonDirs, $cacheDir, $env)
    {
        $this->fileLocator = $fileLocator;
        $this->modeDirs = $modeDirs;
        $this->addonDirs = $addonDirs;
        $this->themesDirs = $themesDirs;
        $this->cacheDriver = new PhpFileCache($cacheDir);
        $this->env = $env;
        #check env and fetch cache
        if ($this->env == 'prod' && $cacheModes = $this->cacheDriver->fetch(static::CACHE_MODES_NAME)) {
            $this->modes = $cacheModes;
        } else {
            $this->parseModes();
        }

        if ($this->env == 'prod' && $cacheAddons = $this->cacheDriver->fetch(static::CACHE_ADDONS_NAME)) {
            $this->addons = $cacheAddons;
        } else {
            $this->parseAddons();
        }

        if ($this->env == 'prod' && $cacheThemes = $this->cacheDriver->fetch(static::CACHE_THEMES_NAME)) {
            $this->themes = $cacheThemes;
        } else {
            $this->parseThemes();
        }
    }

    /**
     * Parse editor mode from dir
     */
    protected function parseModes()
    {
        foreach ($this->modeDirs as $dir) {
            $absDir = $this->fileLocator->locate($dir);

            $finder = Finder::create()->files()->in($absDir)->name('*.js');

            foreach ($finder as $file) {
                $this->addModesFromFile($file);
            }
        }
        #save to cache if env prod
        if ($this->env == 'prod') {
            $this->cacheDriver->save(static::CACHE_MODES_NAME, $this->getModes());
        }
    }

    /**
     * Parse editor modes from dir
     */
    protected function addModesFromFile($file)
    {
        $jsContent = $file->getContents();
        preg_match_all('#defineMIME\(\s*(\'|")([^\'"]+)(\'|")#', $jsContent, $modes);
        if (count($modes[2])) {
            foreach ($modes[2] as $mode) {
                $this->addMode($mode, $file->getPathname());
            }
        }

        #save to cache if env prod
        if ($this->env == 'prod') {
            $this->cacheDriver->save(static::CACHE_MODES_NAME, $this->getThemes());
        }
    }

    protected function addAddonsFromFile($file)
    {
        $jsContent = $file->getContents();
        preg_match_all('/CodeMirror.defineExtension\("([a-z0-9A-Z]+)"/', $jsContent, $addons);
        if (count($addons[1])) {
            foreach ($addons[1] as $mode) {
                $this->addAddon($mode, $file->getPathname());
            }
        }

        #save to cache if env prod
        if ($this->env == 'prod') {
            $this->cacheDriver->save(static::CACHE_MODES_NAME, $this->getThemes());
        }
    }

    public function addMode($key, $resource)
    {
        $this->modes[$key] = $resource;

        return $this;
    }

    public function addAddon($key, $resource)
    {
        $this->modes[$key] = $resource;

        return $this;
    }

    public function getThemes()
    {
        return $this->themes;
    }

    public function getModes()
    {
        return $this->modes;
    }


    protected function parseAddons()
    {
        foreach ($this->addonDirs as $dir) {
            $absDir = $this->fileLocator->locate($dir);

            $finder = Finder::create()->files()->in($absDir)->name('*.js');

            foreach ($finder as $file) {
                $this->addAddonsFromFile($file);
            }
        }
        #save to cache if env prod
        if ($this->env == 'prod') {
            $this->cacheDriver->save(static::CACHE_ADDONS_NAME, $this->getAddons());
        }
    }

    /**
     * @return array|false|mixed
     */
    public function getAddons()
    {
        return $this->addons;
    }

    /**
     * @param array|false|mixed $addons
     */
    public function setAddons($addons)
    {
        $this->addons = $addons;
    }

    /**
     * Parse editor themes from dir
     */
    protected function parseThemes()
    {
        foreach ($this->themesDirs as $dir) {
            $absDir = $this->fileLocator->locate($dir);
            $finder = Finder::create()->files()->in($absDir)->name('*.css');
            foreach ($finder as $file) {
                $this->addTheme($file->getBasename('.css'), $file->getPathname());
            }
        }
        #save to cache if env prod
        if ($this->env == 'prod') {
            $this->cacheDriver->save(static::CACHE_THEMES_NAME, $this->getThemes());
        }
    }

    public function addTheme($key, $resource)
    {
        $this->themes[$key] = $resource;

        return $this;
    }

    public function getMode($key)
    {
        return isset($this->modes[$key]) ? $this->modes[$key] : $key;
    }

    public function getTheme($key)
    {
        return isset($this->themes[$key]) ? $this->themes[$key] : false;
    }
}

