PPAU's amazingly confusing voting system
========================================

Requirements
------------

*   Apache 2
*   mod_rewrite `a2enmod rewrite`
*   PHP 5.2+ (Apache module + PHP CLI for scripts)
*   PHP gmp module `apt-get install php5-gmp`
*   ZendFramework `apt-get install zendframework`
*   MySQL
*   Working MTA

Setup
-----

1.  Import SQL from `ppauvote.sql`
2.  Create poll in `polls` table:  
    `INSERT INTO polls (name, active) VALUES ("Poll Name", 1);`  
    Leave the key fields as NULL - we'll generate those using script
3.  Find the `pollid`  
    `SELECT id, name FROM polls;`
4.  Set config in `application/configs/application.ini`
    * Set DB config
    * Set SMTP/MTA config
5.  Create keys for poll  
    `./scripts/make-poll-keys.php pollid`  
    pollid is the poll ID from step 3
6.  Edit page/email templates etc.
    * `application/views/scripts/vote/view.phtml`
        * modify the success note
    * `application/views/scripts/vote/poll-x.phtml`
        * x == pollid from step 3
        * build your vote form in here with JS validation
        * no validation is done on server, you must validate vote data when you process
          the data after the poll has closed
    * `scripts/templates/mailout-x.phtml`
        * x == pollid from step 3
        * email template that will be sent to participants including their unique vote key
7.  Add your own personal details to a record in `participants` for testing
8.  Run test mailout  
    `./scripts/mailout.php pollid`  
    pollid is the poll ID from step 3
9.  Click the link in email and do a test vote
10. Dump the vote data and check to see it looks good  
    `./scripts/dump.php pollid`  
    pollid is the poll ID from step 3
11. Clear all data  
    `./scripts/clear-all.php`  
12. Empty the participants table and import the real participants
13. Perform real mailout  
    `./scripts/mailout.php pollid`  
    pollid is the poll ID from step 3
14. Vote!

Closing and processing the poll
-------------------------------

1.  Disable the poll  
    `UPDATE polls SET active = 0 WHERE id = pollid;`
2.  Dump the data for external processing  
    `./scripts/dump.php pollid`  
    __IMPORTANT NOTES__:
    *   The data is NOT validated, people can submit whatever the fuck they want if the JS on their browser lets them.
    *   You MUST validate all fields post-dump, ditch the vote if it doesn't validate
    *   Currently the dump.php script DOES NOT validate the signatures, 
        that means that if someone has tampered with DB it will go unnoticed.

Credits
-------

Originally written by ashaw.

All crypto and stuff written by ashaw (and butchered by sdunster)


PHP converted to run on ZendFramework (MVC) by sdunster

AJAXy JS interface added by sdunster

Coped with Brendan's bitching by sdunster

