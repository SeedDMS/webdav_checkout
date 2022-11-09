WebDAV access on checkout directory
====================================

This extension provides a WebDAV server for serving the check out
space of each user.

You will need to add an alias to your apache configuration

    Alias /checkout <document root>/ext/webdav_checkout/op/remote.php

Replace `<document root>` with your DocumentRoot

If you like to change `/checkout` to something different, you will also
have to set the base Uri in the extension's configuration to your
desired value.

Any user uses the same url (https://your-domain/checkout) but will
see only its own check out space.
