#!/usr/bin/env bash
while [ true ]
do
red=$(gpio read 0)
yellow=$(gpio read 2)
green=$(gpio read 1)
blue=$(gpio read 4)

if [ ${red} -eq 1 ]
then curl 192.168.1.101:8080/api/insertBuzzer/4
sleep 1
fi

if [ ${yellow} -eq 1 ]
then curl 192.168.1.101:8080/api/insertBuzzer/3
sleep 1
fi

if [ ${green} -eq 1 ]
then curl 192.168.1.101:8080/api/insertBuzzer/2
sleep 1
fi

if [ ${blue} -eq 1 ]
then curl 192.168.1.101:8080/api/insertBuzzer/1
sleep 1
fi
echo "durchlauf"
sleep 0.1
done
