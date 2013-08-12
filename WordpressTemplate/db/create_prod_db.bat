@ECHO OFF
ECHO DO NOT DO THIS!! THIS WILL DESTROY THE PRODUCTION DATABASE!!!!
ECHO Really create database on Gaia? Ctrl+c to cancel
pause
type create_db.sql use_db.sql __safe_name__.sql create_user.sql > tmp.sql
mysql -h gaia.aristotle.net -u %USERNAME% -p mysql < tmp.sql
del tmp.sql
pause
