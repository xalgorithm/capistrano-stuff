-- There is no DROP USER IF EXISTS.
-- Do a GRANT to assure the user exists before dropping and recreating
GRANT USAGE ON * . * TO  '__safe_name__'@'localhost' IDENTIFIED BY  '__db_pass__';
GRANT USAGE ON * . * TO  '__safe_name__'@'%' IDENTIFIED BY  '__db_pass__';
DROP USER '__safe_name__'@'localhost';
DROP USER '__safe_name__'@'%';

CREATE USER '__safe_name__'@'localhost' IDENTIFIED BY  '__db_pass__';
CREATE USER '__safe_name__'@'%' IDENTIFIED BY  '__db_pass__';

GRANT USAGE ON * . * TO  '__safe_name__'@'localhost' IDENTIFIED BY  '__db_pass__' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
GRANT USAGE ON * . * TO  '__safe_name__'@'%' IDENTIFIED BY  '__db_pass__' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

GRANT ALL PRIVILEGES ON  `__safe_name__` . * TO  '__safe_name__'@'localhost';
GRANT ALL PRIVILEGES ON  `__safe_name__` . * TO  '__safe_name__'@'%';
