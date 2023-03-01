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

Accessing the check out space
------------------------------

If access by WebDAV is not possible, e.g. because you are working at
a computer without the required network drive, there is another way
to access a checked out file. If turned on in the configuration of
this extension, a currently checked out file can be downloaded to
your local disc and a file from your local disc can be uploaded
to replace the checked out file. 
