# This Document

This document is an _on-the-fly_ documentation for the special-pancake project. 
It is written, using markdown as described in 
http://daringfireball.net/projects/markdown/syntax

This document is meant to contain vital notes to understand the structure of
the project. It is __not__ meant to be a complete documentation.

# Configuration

The global configuration is realized by two files: _/localconf.ini_ and
_/core/config.ini_  
_/core/config.ini_ contains the default value for each config variable.
In _localconf.ini_ these values can be overritten for the local installation.

In the source code, the configuration can be accessed, using the global variable
_$config_.

# The core, modules and plugins

## The core

The core includes _index.php_ and the folder _/core_ with all its contents. It
is the backbone of this project and should not be tempered with.

## Modules

Modules are packages, that are not part of the core but still needed for the
project to run properly. They can be exchanged, but not removed.
Every module is registered in _/core/config.ini_ - To exchange a module,
just overwrite the given path with the path of your own module.

## Plugins

Plugins are packages, that add additional features, but are not needed for the
project to work. They can be added and removed at will. Be careful though.
Plugins still can depend on each other.

## File structure of modules/plugins

Every module/plugin has its own folder, containing only this m/p and everything,
that belongs to this m/p. In the m/p-folder has to be a file named _config.ini_
containing information on how the m/p is to be loaded.

### [classes]

The section __classes__ is a list of files, containing code that is not
immediately executed (classes and functions). These files are included first
and without a guaranteed order.  
If a plugin contains classes, they should use the name of the plugin as a
prefix, to prevent conflicts.

### [execute]

The section __execute__ contains files to be executed, when the plugin is fully
loaded. These files are sortet by their key, so it makes sense to give them a
prefix to be sorted by.

