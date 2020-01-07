<?php

namespace Benfurfie\StatamicSvg\Modifiers;

use Exception;
use Statamic\Facades\Asset;
use Statamic\Tags\Tags;
use InlineSvg\Collection;
use InlineSvg\Transformers\Cleaner;

class Svg extends Tags
{
    /**
     * The {{ svg }} tag
     *
     * @return mixed
     * @throws Exception
     */
    public function index()
    {
        $file = $this->getParam('src');

        if (! $file) {
            throw new Exception('To use the SVG helper, you must specify a \'src\' parameter');
        }

        $name = $this->getFileName($file);

        if (! $name) {
            throw new Exception(sprintf('File [%s] doesn\'t exist.', $file));
        }

        $container = $this->getContainerUrl($file);
        $path = $this->getFilePath($file);
        $folder = $this->getAssetFolder($path);
        $fullPath = $this->getFullAssetFolderPath($container, $folder);

        return $this->parseFile($fullPath, $name);
    }

    /**
     * @param string $fullPath
     * @param string $name
     * @return \InlineSvg\Svg
     */
    private function parseFile(string $fullPath, string $name): \InlineSvg\Svg
    {
        // Set up the InlineSVG collection
        $collection = $this->getCollection($fullPath);

        // Check to see if we want to allow IDs. If we do, move on; else scrub any IDs.
        $allowIds = $this->getParam('allowIds');
        if (!$allowIds) {
            $collection->addTransformer(new Cleaner());
        }

        // Check to see if there is any accessibility text for the SVGs.
        $a11y = $this->getA11y();

        // Get any config (classes/height/width etc) from the SVG tag.
        $config = $this->getSvgConfig();

        // Return the SVG with config and a11y.
        return $collection->get($name)->withAttributes($config)->withA11y($a11y);
    }

    /**
     * Getter for the a11y parameter in the tag.
     *
     * @return string|null
     */
    private function getA11y(): ?string
    {
        return $this->getParam('a11y');
    }

    /**
     * Getter for the class, height, and width parameters in the tag.
     *
     * @return array
     */
    private function getSvgConfig(): array
    {
        // Get the config from the params
        $config = [
            'class' => $this->getParam('class'),
            'height' => $this->getParam('height'),
            'width' => $this->getParam('width'),
        ];

        return array_filter($config, function ($element) {
            return $element;
        });
    }

    /**
     * Init a collection from InlineSVG
     *
     * @param string $fullPath Pass through the path to the SVG's containing folder
     * @return Collection
     */
    private function getCollection($fullPath): Collection
    {
        return Collection::fromPath($fullPath);
    }

    /**
     * Gets and returns the asset folder path as a string.
     *
     * @param string $path The path to the file
     * @return string
     */
    private function getAssetFolder($path): string
    {
        // First, explode and then reverse the array.
        // We do this so we can easily remove the file itself.
        $reversedArray = array_reverse(explode('/', $path));

        // Now the array is flipped, remove the file.
        array_shift($reversedArray);

        // Reverse the reversed array back, convert to string, and return.
        return implode('/', array_reverse($reversedArray));
    }

    /**
     * Get the full folder path for the SVG being called.
     * This is used by InlineSVG's collection class.
     *
     * @param string $container
     * @param string $folder
     * @return string
     */
    private function getFullAssetFolderPath($container, $folder): string
    {
        return sprintf('%s%s/%s', getcwd(), $container, $folder);
    }

    /**
     * Get the asset's container URL.
     *
     * @param string The path to the SVG
     * @return string
     */
    private function getContainerUrl($file): string
    {
        $asset = Asset::find($file);
        $container = $asset->container();
        return $container->url();
    }

    /**
     * Get the name of the asset.
     *
     * @param string The path to the SVG
     * @return string
     */
    private function getFileName($file): ?string
    {
        $asset = Asset::find($file);

        return $asset ? $asset->filename() : null;
    }

    /**
     * Get the path to the asset file.
     *
     * @param string The path to the SVG
     * @return string
     */
    private function getFilePath($file): string
    {
        $asset = Asset::find($file);

        return $asset->path();
    }
}
