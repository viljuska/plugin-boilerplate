# Plugin boilerplate

<!-- TOC -->
* [Plugin boilerplate](#plugin-boilerplate)
  * [Description](#description)
    * [Required](#required)
    * [Technologies](#technologies)
  * [Setup](#setup)
<!-- TOC -->

## Description

This plugin has OOP setup with namespaces and autoloading. It also has webpack setup for development and production.
Webpack setup is created for using Bootstrap 5 and SCSS. There is also Babel setup for ES6+. There is postcss setup for autoprefixing and
minification of CSS. There is also setup for minification of JS files. There is also setup for copying files from `src` to `dist` folder.

There are couple presets for postcss, by default there are presets for:

* combining media queries and removing comments and whitespace.
* autoprefixing and minification of CSS.
* preset for sorting media queries by desktop first - There could be potential problems in css rules prioritisation, but I haven't encountered in my projects.

There is package called standard-version for automatic versioning of plugin. It uses Conventional Commits for versioning.
You can check here documentation for [standard-version](https://github.com/conventional-changelog/standard-version/tree/master) and you can see here standards that this package uses [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/).

### Required

- Composer >= 2.0
- NodeJS >= 18.0.0
- PHP >= 7.4
- WordPress >= 6.0

### Technologies

- HTML
- CSS/SCSS
	- Bootstrap 5
	- SCSS
- Javascript/ES6+
	- Babel
- PHP 7+

## Setup

1. Clone repository
2. Run `composer install`
3. Run `npm install` or `yarn install` or `pnpm install` and then use required tool for development
4. Run `npm run watch` for development
5. Run `npm run build:production` for production
6. Activate plugin in WordPress
7. Enjoy :) 
