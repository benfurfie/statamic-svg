<?php

namespace Statamic\Addons\Svg;

use InlineSvg\Collection;
use InlineSvg\Transformers\Cleaner;
use Statamic\API\Asset;
use Statamic\Extend\Tags;

/**
 * Call in autoloader
 */
include __DIR__ . '/vendor/autoload.php';

class SvgTags extends Tags
{
    /**
     * The {{ svg }} tag
     * 
     *
     * @return string|array
     */
    public function index()
    {
        /**
         * Grab the path to the installation as InlineSvg requires it.
         */
        $webroot = getcwd();

        /**
         * Get the folder of the asset we're looking to manipulate.
         */
        // First, let's get the path to the asset from the params.
        $src = $this->getParam('src');

        // Then, let's find the asset.
        $asset = Asset::find($src);

        // Next, let's grab its container object.
        $container = $asset->container();

        // We also need its filename.
        $filename = $asset->filename();

        // After we're got that, let's use the URL helper to get the path to the folder.
        $folder = $container->url();

        // Next, we need to get the name of the folder the SVGs are stored in.
        // Eventually, we'll configure this to either pull out of the path or be
        // settable through the CP. But for now, we'll hardcode it to /svgs.

        // Get the path of the asset.
        $assetPath = $asset->path();
        
        // Explode the path of the asset into an array.
        $assetFolder = explode('/', $assetPath);

        // Take that array and starting at 0, remove the last item (which will be the file itself).
        $assetPathArray = array_slice($assetFolder, 0, -1);

        // Implode the path so it can be used as part of the folder path.
        $assetPathTrue = implode('/', $assetPathArray);
        
        $folder = $folder . '/' . $assetPathTrue;
        
        // Finally, we need to combine the $webroot and $folder.
        // This provides us with the full path for the method call below.
        $folder = $webroot . $folder;

        /**
         * Configure InlineSvg
         * The Collection::fromPath method needs to be the full path otherwise it doesn't work.
         * Hence all the work above.
         */
        $svgs = Collection::fromPath($folder);

        /**
         * Check if we allow are allowing IDs. If we are, do nothing.
         * Else, scrub IDs. Be warned, if your SVGs use IDs for styling, they will lose that styling.
         * Hence, this is set to false by default.
         */
        $allowIds = $this->getParam('allowIds');
        if(!$allowIds)
        {
            $svgs->addTransformer(new Cleaner());
        }

        /**
         * Pull in all the other parameters that we can use.
         */
        $classes = $this->getParam('classes');
        $a11y = $this->getParam('a11y');
        $height = $this->getParam('height');
        $width = $this->getParam('width');

        /**
         * Handle SVG manipulation
         */
        // First check if the values exist and if they do, set the them to value of the param.
        $args = [
            'class' => ((false) ? null : $classes),
            'height' => ((false) ? null : $height),
            'width' => ((false) ? null : $width)
        ];

        // Filter the array so we remove any null values.
        $args = array_filter($args, 'strlen');

        // Pass the args to the function and set it ready for output.
        $output = $svgs->get($filename)->withAttributes($args)->withA11y($a11y);
        
        return $output;        
    }

}
