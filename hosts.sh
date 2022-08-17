#!/bin/bash

declare -A array_name

array_name[main.loc]=10.5.0.5
array_name[www.main.loc]=10.5.0.5
array_name[pma.loc]=10.5.0.10
array_name[www.pma.loc]=10.5.0.10

for i in "${!array_name[@]}"; do

  if sudo grep $i /etc/hosts; then
    echo "Хост "$i" уже настроен"
  else
    sudo -- sh -c "echo "${array_name[$i]}"  "$i" >> /etc/hosts"

    echo "Хост "$i" добавлен"
  fi

done
