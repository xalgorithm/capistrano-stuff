Dev Machine Setup
=================
This assumes you've followed the instructions here: http://wiki.aristotle.net/index.php?title=PHP_Dev_Machine_Setup
(tl;dr install XAMPP and start apache and mysql services)

DB Maintenance through the Dev Cycle
====================================
At the start of the project (before any dev), you can create the basic local database with /db/create_db_local.bat

After some dev on your local machine(s) results in content or drupal config changes that you want to save, you should update the sql dump. Run /db/dump_db_local.bat and commit the generated .sql file. 

At some point you'll be ready to push to the staging server. Make sure the local DB is at latest (dump_db_local) and (assuming you're a prod MySQL admin) run /db/create_db_prod.bat

From this point on, you should only update the sql file from the prod database. After config or content changes on staging, run dump_db.bat and commit the sql file. This doesn't need MySQL admin privileges. Then overwrite your local DB from the new file: create_local_db.bat

