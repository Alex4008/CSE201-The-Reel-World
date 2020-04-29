#!/bin/bash
echo 'Running script'
rm -rf CSE201-The-Reel-World
git clone git@github.com:Alex4008/CSE201-The-Reel-World.git
git pull
echo 'Finished cloning'
echo 'All Web Files:'
ls CSE201-The-Reel-World/*.php
echo 'All SQL Files:'
ls CSE201-The-Reel-World/*.sql
