# SVG

This addon lets you inline and manipulate inline SVGs, including injecting classes, width and height properties, and a11y configuration. "But that's what the [output modifier](https://docs.statamic.com/tags/theme-output) is for!", I hear you cry. Believe me — this addon will make your life so much easier, that you'll want to cry and name your first-born after me... okay, that's maybe too far, but it will make your life a hell of a lot easier.

## Installation
Download the [zip file](https://github.com/benfurfie/statamic-svg/archive/master.zip) and add the `svg` folder to your Statamic Addons folder (typically `/site/addons/`). Then, in the command line, run `php please update:addon` from your Statamic root path. Composer will update the plugin, which you can check on your Addons view in the control panel. 

## Version 2
This is version two of the plugin. I've pretty much refactored the entire addon and changed some bits that I wasn't happy with. **The only breaking change** is to the CSS parameter: this is now `class`, rather than `classes`. Other than that, it remains the same.

## Usage
The only parameter that is necessary with this tag is `src` (otherwise, it won't know what to output).

You can define this as a path to your asset container, or you can refer to a fieldset key (note the single brackets).

```yaml
{{ svg src="/assets/svg/phone.svg" }}
```

or 

```yaml
---
icon: /assets/svg/phone.svg
---

{{ svg src="{ icon }" }}
```

will work. As I said before, the only crucial thing at the moment is that you need to store your SVGs in an assets container.

## Additional parameters
Okay, so that's cool, but that's nothing `{{ icon | output }}` couldn't do. The real power of the addon is that you can pass through additional parameters and have them output in the SVG.

### Injecting CSS Classes
You can pass classes into your SVG by using the `class` parameter. This is especially useful if you use TailwindCSS and want to avoid having to create non-class-based CSS.

Let's say you have a solid icon you want to change the colour of. Maybe it's pulled through from the font awesome library and so you can't edit the code, otherwise it'll get wiped out next time you update it. (Want to hazard a guess as to why I built this addon?).

All you'd need to do if you wanted to make that SVG teal is pass through the following classes:

```yaml
---
icon: /assets/svg/phone.svg
---

{{ svg src="{ icon }" class="fill-current text-teal" }}
```

You can also pass through height, width and whatever other classes you want to, including its focus, hover, media and active states.

### Width and Height
Want to set the width and the height of an SVG without editing the code of the SVG itself? Easy...

```yaml
---
icon: /assets/svg/phone.svg
---

{{ svg src="{ icon }" width="40" height="40" }}
```

### Accessibility (a11y)
Want to add a title to an SVG to enable those using screen readers to be able to understand what an SVG is all about? Simply pass through something like below and it'll add a title to the SVG.

```yaml
---
icon: /assets/svg/phone.svg
---

{{ svg src="{ icon }" a11y="An icon of a phone to indicate that this is part of a phone number" }}
```

Of course, you can also pass through any page data you want to as well, meaning anyone using the CP can also add accessiblity text. For example, if they add a title to an asset, you can pull it through using the [assets tag](https://docs.statamic.com/tags/assets#single-assets) like so:

```yaml
---
icon: /assets/svg/phone.svg
---

{{ assets:icon }}
    {{ svg src="{ url }" a11y="{ title }" }}
{{ /assets:icon }}
```

*Image credit: [Pankaj Patel – Unsplash](https://unsplash.com/photos/Ylk5n_nd9dA)*
