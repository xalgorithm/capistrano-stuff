@ECHO OFF
ECHO Really create database __safe_name__? Ctrl+c to cancel
pause
type create_db.sql use_db.sql __safe_name__.sql create_user.sql > tmp.sql
ECHO This needs your local MySQL root password (XAMPP default is blank)
mysql -u root -p mysql < tmp.sql
del tmp.sql
pause
