# MDIV Downloader
This is a script that, given a Canvas LMS auth token, download course information for the account identified by that token.  The token should be located in a `.env` file in the same folder as this readme.  That file should not be shared with anyone else because nefarious people can do nasty stuff to or with your account if it gets out in the wild.

Once a course is downloaded, its ID and name are added  to a file, completed.json, that prevents it from being re-downloaded.  If you need/want to re-download a course, just remove it from that file.
