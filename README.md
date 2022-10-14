WebDAV access on checkout directory
====================================

You will need to add an alias to your apache configuration

    Alias /checkout <document root>/ext/webdav_checkout/op/remote.php

Replace `<document root>` with your DocumentRoot

If you like to change `/checkout` to something different, you will also
have to set the base Uri in the extension's configuration to your
desired value.
