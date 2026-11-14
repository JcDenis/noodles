# noodles

Notes for AI assistants working on the Dotclear plugin noodles.

**Read first** the shared conventions of the author's Dotclear modules:
https://raw.githubusercontent.com/JcDenis/gh2da/master/CONVENTIONS.md
(if a web tool refuses the address, use `curl -s` on it from the shell).
The rules below take precedence for this module.

## Module

Add gravatars everywhere it is possible on your blog. comments, posts, widgets.

Admin page. Public side: URL handlers.

Required Dotclear and PHP versions: see `_define.php`.

## Structure

* `src/Backend.php`: admin side: menu entry, favorites, behaviors registration.
* `src/Combo.php`: combo helper.
* `src/Frontend.php`: public side: behaviors, template tags and URL handlers registration.
* `src/Image.php`: image helper.
* `src/Install.php`: install and update, default settings.
* `src/Manage.php`: admin page of the module.
* `src/My.php`: module helpers (`My::id()`, settings, permissions).
* `src/Prepend.php`: loaded on every request: autoload, URL handlers, permission types.
* `src/Target.php`: target definition.
* `src/Targets.php`: targets stack.
* `src/Uninstall.php`: cleaners for the plugin Uninstaller.
* `src/UrlHandler.php`: URL handler.
* `src/Target/AuthorMode.php`: target rendering for plugin authorMode.
* `src/Target/Generic.php`: target generic rendering.
* `src/Target/Other.php`: target complete rendering.
* `src/Target/Widgets.php`: target rendering for plugin widgets.
* `default-templates/`: public templates.
* URL handlers: `noodles_file`, `noodles_service`, `noodles_css`, `noodles_js`.
* Behaviors used: `publicHeadContent`.

## Pitfalls

* None recorded yet: add here the traps met while working on the module.

## Testing

* Check the admin page, the public URLs, in English and French.
* CI browses the common admin and public pages; extra pages can be listed in `.github/dotclear-test.txt`.
