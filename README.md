WebDAV access on checkout directory
====================================

This extension provides a WebDAV server for serving the check out
space of each user.

You will need to add an alias to your apache configuration

    Alias /checkout <document root>/ext/webdav_checkout/op/remote.php

Replace `<document root>` with the location of your SeedDMS installation.

If you like to change `/checkout` to something different, you will
also have to set the base Uri in the extension's configuration to your
desired value.

All users use the same url (e.g. https://your-domain/checkout), but
will see only its own check out space.

Accessing the check out space
------------------------------

If access by WebDAV is not possible, e.g. because you are working at a
computer without the required network drive, there is another way to
access a checked out file (available since version 1.1.0 of this
extension).  If turned on in the configuration of this extension, a
currently checked out file can be downloaded to your local disc and a
file from your local disc can be uploaded to replace the checked out
file.

Since version 1.1.1 it is even possible to allow any user or only the
user who checked out the document to download or upload it.
