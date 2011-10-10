#!/bin/sh

dbfile="roommates.sql"
dbname="roommates"
declare -a data
declare -a samples

data=( "insert-floors.sql" "insert-heating_types.sql" "insert-house_types.sql" "insert-municipalites.sql" )
samples=( "insert-sample-users.sql" "insert-sample-profiles.sql" "insert-sample-houses.sql" )

echo -n "Enter your mysql user to connecto to db (enter for root) > "
read dbuser
if [[ -z $dbuser ]]; then
    dbuser="root"
fi

echo -n "Enter your mysql password for user ${dbuser} > "
stty -echo
read dbpass
stty echo
echo -e "\n"

echo -n ">> Inserting database schema..."
mysql -u${dbuser} -p${dbpass} < ${dbfile}
echo "DONE"

echo -n ">> Inserting database data..."
for i in ${data[@]}; do
    mysql -u${dbuser} -p${dbpass} ${dbname} < "${i}"
done
echo "DONE"

echo -n ">> Inserting sample data..."
for i in ${samples[@]}; do
    mysql -u${dbuser} -p${dbpass} ${dbname} < "${i}"
done
echo "DONE"
