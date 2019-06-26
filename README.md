# SVG
Allows you to manipulate inline SVGs, including injecting classes, width and height properties, and a11y configuration.

## Version 2
This is version two of the plugin. I've pretty much refactored the entire addon and changed some bits that I wasn't happy with.

Breaking changes are listed below:

### Classes
This parameter is now `class`, rather than `classes`. Other than that, it remains the same.

Also, due to some changes, it is no longer necessary to run `php please addons:update` before you can use it. It will still say it isn't installed, but you can ignore that!

## Usage
This tag will output an inline SVG. But that's what the output modifier is for I hear you cry. Believe me. This addon will make your life so much easier that you'll want to cry and name your first born after me... okay, that's maybe too far, but it will make your life a hell of a lot easier.

The only parameter that is necessary with this tag is src (otherwise, what will it output).

You can define this as a path to your asset container, or you can refer to a fieldset key.

```Handlebars
{{ svg src="/assets/svg/phone.svg" }}
```

or 

```Handlebars
---
icon: /assets/svg/phone.svg
---

{{ svg src="{ icon }" }}
```

will work. As I said before, the only crucial thing at the moment is that you need to store your SVGs in an assets container.

## Additional parameters
Okay, so that's cool, but that's nothing `{{ icon | output }}` couldn't do. The real power of the addon is that you can pass through additional parameters and have them output in the SVG.

### Classes
You can pass classes into your SVG by using the `class` parameter. This is especially useful if you use TailwindCSS and want to avoid having to create non-class-based CSS.

Let's say you have a solid icon you want to change the colour of. Maybe it's pulled through from the font awesome library and so you can't edit the code, otherwise it'll get wiped out next time you update it. (Want to hazard a guess as to why I built this addon?).

All you'd need to do if you wanted to make that SVG teal is pass through the following classes:

```Handlebars
---
icon: /assets/svg/phone.svg
---

{{ svg src="{ icon }" class="fill-current text-teal" }}
```

You can also pass through height, width and whatever other classes you want to, including its focus, hover, media and active states.

### Width and Height
Want to set the width and the height of an SVG without editing the code of the SVG itself? Easy...

```Handlebars
---
icon: /assets/svg/phone.svg
---

{{ svg src="{ icon }" width="40" height="40" }}
```

### A11Y
Want to add a title to an SVG to enable those using screen readers to be able to understand what an SVG is all about? Simply pass through something like below and it'll add a title to the SVG.

```Handlebars
---
icon: /assets/svg/phone.svg
---

{{ svg src="{ icon }" a11y="An icon of a phone to indicate that this is part of a phone number" }}
```

Of course, you can also pass through any page data you want to as well, meaning anyone using the CP can also add accessiblity text. For example, if they add a title to an asset, you can pull it through using the [assets tag](https://docs.statamic.com/tags/assets#single-assets) like so:

```Handlebars
---
icon: /assets/svg/phone.svg
---

{{ assets:icon }}
    {{ svg src="{ url }" a11y="{ title }" }}
{{ /assets:icon }}
```

*Image credit: [Pankaj Patel – Unsplash](https://unsplash.com/photos/Ylk5n_nd9dA)*
