#!/bin/bash

this_dir=$(cd `dirname $0`;pwd)

. $this_dir/../etc/piLogger.conf
. $this_dir/functions

TS=$(date "+%Y%m%d_%H%M%S")
backupFile=${TS}.tgz

[ -z "$backupDir" ] && { logIt "Error: backupDir is not set in $this_dir/../etc/piLogger.conf, exiting." ; exit 1 ; }
[ ! -d $backupDir ] && mkdir -p $backupDir
[ ! -d $backupDir ] && { logIt "Error: $backupDir does not exist, and is not possible to create, exiting"; exit 1; }

#=====================================================
# Functions
#=====================================================
function usage(){
cat<<EOT

Usage:

  $this_script [ -l | --list | -h | --bare ] FILENAME.tgz

Examples:

  $this_script                            # this help screen
  $this_script -h                         # this help screen
  $this_script --bare /path/to/file       # restore bare metal dump (rrdtool restore, app.sqlite3), normally used to
                              # set up a new development environment
  $this_script --list                     # list files in $backupDir
  $this_script /path/to/file              # normal restore, including crontab and remote-logging-enabled links

Description:


EOT

}

function listFiles(){

	echo "* Listing of files in $backupDir"
	echo
	
	ls -lA $backupDir
}


function restoreCrontab(){
  local crontabFile="$1"
  [ ! -f "$crontabFile" ] && { echo "Error: no crontab file found: $crontabFile" ; return 1 ; }

  cat $crontabFile | crontab -
  return 0

}

function restoreRemoteLoggingScripts(){
  local linkFile="$1"

  [ ! -f "$linkFile" ] && { echo "Error: no link file found: $linkFile" ; return 1 ; }

  OLD_IFS=$IFS
  IFS='
'
  for line in $(cat $linkFile | grep -v "^\* ->" )
  do
    IFS=$OLD_IFS
    fileName=$(echo $line | awk -F" -> " '{print $1}')
    linkDestination=$(echo $line | awk -F" -> " '{print $2}')

    echo "    - Creating link: $fileName -> $linkDestination"
    ln -s $linkDestination $dataDir/remote-logging-enabled/$fileName
  done

  
}


function normalRestore(){
  local backupFile="$1"
  [ ! -f "$backupFile" ] && { echo "Error: no backup file: $backupFile" ; exit 1 ; }

  echo "  - Restoring files from: $backupFile"
  echo
  cd /
  tar xvzf "$backupFile"

  echo
  echo "  - Restoring crontab from $dbDir/crontab.latest"
  echo
  restoreCrontab $dbDir/crontab.latest

  echo "  - Restoring symlinks in remote-logging-enabled from $dbDir/remote-logging-enabled.links.latest"
  echo
  restoreRemoteLoggingScripts $dbDir/remote-logging-enabled.links.latest
  
  return 0
}



function restoreBareMetal(){

    restoreDir=$(cd `dirname "$1"`;pwd)
	restoreFileName=$(basename "$1")
	restoreFile="${restoreDir}/${restoreFileName}"
    tmpDir=$(mktemp -d $backupDir/rrdDump.tmpDir.XXXXXX)

	echo "* Restoring from bare metal backup file: $restoreFile"
	
	[ ! -f "$restoreFile" ] && { echo "ERROR: file $restoreFile not found" 1>&2 ; exit 1 ; }
	echo "  - Creating tmp dir $tmpDir"

	cd $tmpDir

	echo "  - Extracting tar file $restoreFile"
	echo
	tar xf $restoreFile
	ls -1 *.gz | xargs -L1 -P4 -IXXXX sh -c "echo extracting XXXX ; gunzip XXXX "


	echo
	echo "  - Removing old rrd files in $dbDir"
	rm $dbDir/*.rrd

	echo "  - Importing xml files"
	echo
    ls -1 *.xml | xargs -L1 -IX basename X .rrd.xml | xargs -L1 -P4 -IXXXX sh -c "echo restoring XXXX.rrd.xml to $dbDir/XXXX.rrd; rrdtool restore XXXX.rrd.xml $dbDir/XXXX.rrd -f" 	

	echo
	echo "  - Restoring app.sqlite3"
	cp app.sqlite3 $dbDir/app.sqlite3
	
	echo "  - Removing tmpDir: $tmpDir"
	echo
	echo "  - Running $baseDir/bin/refreshCaches"
	$baseDir/bin/refreshCaches
	
	echo
	echo " * Done"
	rm -rf $tmpDir
	
}
#=====================================================
# MAIN
#=====================================================

[ -z "$1" ] && { usage ; exit 0 ; }

while [ -n "$1" ]
do
  case $1 in
    -h|--help)
      usage
      exit 0
      ;;
    --bare*)
      restoreBareMetal "$2"
      exit 0
      ;;
	--list)
	  listFiles
	  exit 0
	  ;;
    *)
      normalRestore "$1"
      exit
      ;;
  esac
done
