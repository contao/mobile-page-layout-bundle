# Contao 4 mobile page layout bundle

[![](https://img.shields.io/packagist/v/contao/mobile-page-layout-bundle.svg?style=flat-square)](https://packagist.org/packages/contao/mobile-page-layout-bundle)
[![](https://img.shields.io/packagist/dt/contao/mobile-page-layout-bundle.svg?style=flat-square)](https://packagist.org/packages/contao/mobile-page-layout-bundle)

The mobile page layout bundle allows you to have a different page layout for
mobile devices.

Contao is an Open Source PHP Content Management System for people who want a
professional website that is easy to maintain. Visit the [project website][1]
for more information.

## Important note

It is not recommended to use this bundle. It exists mainly for backwards
compatibility reasons. Using a separate page layout based on the client's user
agent header is discouraged. Use CSS media queries and JavaScript instead.

If you enable the mobile page layout, it is possible that the same URL shows
different content. Thus, a shared cache cannot be used. This bundle
therefore overrides your shared page cache settings and always sets the
`Cache-Control` header to `private`.

## License

Contao is licensed under the terms of the LGPLv3.

## Getting support

Visit the [support page][2] to learn about the available support options.

[1]: https://contao.org
[2]: https://contao.org/en/support.html

